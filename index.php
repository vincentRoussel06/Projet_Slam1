<?php 
	session_start();
	require_once('bdd.php');

















	// TODO :
	// afficher score et meilleurs score
	// faire action nourriture
	// enregistrer les tourszs




























	#connexion a BDD 
	$bdd = new BDD();

	/******************************************************** TRAITEMENT ************************************************************/

	// determine la page à afficher en fonction du param GET envoyer
	if(isset($_GET['Jouer'])){
		if(isset($_SESSION["Name"])){
			$AFFICHAGE = initPartie();
		}else{
			echo '<script type="text/javascript"> alert("Vous devez être connecté");</script>';
			$AFFICHAGE = "\Connexion.html";
		}	
	}
	else if(isset($_GET['Citoyen'])){
		$AFFICHAGE = "\Nvo_citoyen.html";
	}
	else if(isset($_GET['Chasseur'])){
		$AFFICHAGE= affectationCitoyen($_SESSION["joueur"]);
	}
	else if(isset($_GET['Constructeur'])){
		$AFFICHAGE = actionChasseur($_SESSION["joueur"]);
	}
	else if(isset($_GET['Assassin'])){
		$AFFICHAGE = actionConstructeur($_SESSION["joueur"]);
	}
	else if(isset($_GET['Resume'])){
		$AFFICHAGE = actionAssassin($_SESSION["joueur"]); // verif fin de partie ici
		// Change de joueur à la fin de chaque tour
		$_SESSION['joueur'] = $_SESSION['joueur']=='a' ? 'b' : 'a'; 
	}
	/********************************** SCORE / BESTSCORE **********************************/
	else if(isset($_GET['score'])){
		$AFFICHAGE = "\Score.html";
	}
	else if(isset($_GET['bestScore'])){
		$AFFICHAGE = "\BestScore.html";
	}
	/********************************** CONNEXION / INSCRIPTION **********************************/
	else if (isset($_GET['deco'])) {
        session_destroy();
        header("Location: index.php");   
    }
    else if (isset($_GET['connexion'])) {
        $AFFICHAGE = "\Connexion.html";   
    }
    else if (isset($_GET['inscription'])) {
        $AFFICHAGE = "\Inscription.html";   
    }
    else if (isset($_GET['validerConnexion'])) { 		#Si l'utilisateur appuie sur le bouton  
    	$AFFICHAGE = validerConnexion();
    }
    else if (isset($_GET['ValiderInscription'])) {		#On verifie si l'utilisateur à valider le formulaire
 		$AFFICHAGE = verifInscription();
    }
	else{
		$AFFICHAGE = "\Accueil.html";
	}

	/******************************************************** AFFICHAGE ************************************************************/

	include("..\Projet_Slam1\Vue\Commun\Top-page.html");
	include("..\Projet_Slam1\Vue\Commun\Header.html");
	include("..\Projet_Slam1\Vue".$AFFICHAGE);
	include("..\Projet_Slam1\Vue\Commun\Footer.html");
	include("..\Projet_Slam1\Vue\Commun\Bottom-page.html");

	/******************************************************** FONCTION ************************************************************/

	function initPartie(){
		/* INIT A CHAQUE DEBUT DE PARTIE*/
		for($i=0;$i<=1;$i++){				// init 2 joueurs
			$lettre = $i==0?'a':'b'; // car un tableau SESSION ne peut avoir d'index num
			$_SESSION[$lettre]["Piege"] = 0;
			$_SESSION[$lettre]["Maison"] = 1;
			$_SESSION[$lettre]["Chasseur"] = 1;
			$_SESSION[$lettre]["Constructeur"] = 1;
			$_SESSION[$lettre]["Assassin"] = 0;
			$_SESSION[$lettre]["Nourriture"] = 100;
		}

		$_SESSION["joueur"] = 'a'; // commence par le premier joueur

		return "/Jouer.html";
	}

	function actionChasseur($Joueur){
		$Enemi = $Joueur=='a' ? 'b' : 'a'; // Trouve l'Enemi
		/* En fonction de l'action des chasseurs */
		if ($_GET['Constructeur']=="chasser") {
			$_SESSION[$Joueur]["Nourriture"] += (10*$_SESSION[$Joueur]["Chasseur"]); // ajoute 10 de nourriture par chasseur
		}else if($_GET['Constructeur']=="vol"){
			if(rand(0,1)==1){	// 1 chance sur deux de perdre la moitier des chasseurs
				$_SESSION[$Joueur]["Chasseur"] = $_SESSION[$Joueur]["Chasseur"]%2;
			}else{
				$_SESSION[$Joueur]["Nourriture"] += (10*$_SESSION[$Joueur]["Chasseur"]);
				$_SESSION[$Enemi]["Nourriture"] -= (10*$_SESSION[$Joueur]["Chasseur"]);
				$_SESSION[$Enemi]["Nourriture"] = $_SESSION[$Enemi]["Nourriture"]<0 ?0:$_SESSION[$Enemi]["Nourriture"];
			}
			
		}

		return "\Action-Constructeur.html";
	}

	function actionConstructeur($Joueur){
		/* En fonction de l'action des constructeurs */
		if ($_GET['Assassin']=="maison") {
			$_SESSION[$Joueur]["Maison"] += (1*$_SESSION[$Joueur]["Constructeur"]);	// Construit 1 maison par constructeurs
		}else if($_GET['Assassin']=="tour"){
			$_SESSION[$Joueur]["Piege"] += (1*$_SESSION[$Joueur]["Constructeur"]);		// Construit 1 piege par constructeurs
		}

		return "\Action-Assassin.html";
	}

	function actionAssassin($Joueur){
		$Enemi = $Joueur=='a' ? 'b' : 'a'; // Trouve l'Enemi

		/* En fonction de l'action des assassins */
		if ($_GET['Resume']=="citoyen") {
			assassinTueCitoyens($Joueur, $Enemi);
		}else if($_GET['Resume']=="maison"){
			assassinDetruitMaison($Joueur, $Enemi);
		}

		return verifFinPartie();
	}

	function assassinTueCitoyens($Joueur, $Enemi){
		// Tue d'abord les Chasseur, puis les constructeurs et enfin les assassins
		for ($i = 0; $i < $_SESSION[$Joueur]["Assassin"]-$_SESSION[$Enemi]["Piege"]; $i++) {
			if($_SESSION[$Enemi]["Chasseur"]>0){
				$_SESSION[$Enemi]["Chasseur"] -= 1;		// Tue 1 citoyen par assassin
			}else if ($_SESSION[$Enemi]["Constructeur"]>0){
				$_SESSION[$Enemi]["Constructeur"] -= 1;	// Tue 1 citoyen par assassin
			}else if($_SESSION[$Enemi]["Assassin"]>0){
				$_SESSION[$Enemi]["Assassin"] -= 1;		// Tue 1 citoyen par assassin
			}else{
				$_SESSION[$Enemi]["Maison"] -= 1;		// Tue 1 citoyen par assassin
			}
		}
	}

	function assassinDetruitMaison($Joueur, $Enemi){
		// baisse au max jusqu'à 0 le nb de maison
		for ($i = 0; $i <= $_SESSION[$Joueur]["Assassin"]-$_SESSION[$Enemi]["Piege"]; $i++) {
			if($_SESSION[$Enemi]["Maison"]>0){
				$_SESSION[$Enemi]["Maison"] -= 1;	
			}
		}
	}

	function affectationCitoyen($Joueur){
		// Affectation des nouveaux citoyen au village
		// Les valeurs sont envoyés grace au formulaire de nvoCitoyen.html
		$_SESSION[$Joueur]["Chasseur"] = $_POST["resChasseur"];
		$_SESSION[$Joueur]["Constructeur"] = $_POST["resConstructeur"];
		$_SESSION[$Joueur]["Assassin"] = $_POST["resAssassin"];

		return "\Action-Chasseur.html";
	}

	function verifFinPartie(){
	    if ($_SESSION['b']["Assassin"]==0 && $_SESSION['b']["Constructeur"]==0 && $_SESSION['b']["Chasseur"]==0 && $_SESSION['b']["Maison"]==0 ){
	    	// Joueur1 Gagne
	    	return "\Fin.html";
	    }else if ($_SESSION['a']["Assassin"]==0 && $_SESSION['a']["Constructeur"]==0 && $_SESSION['a']["Chasseur"]==0 && $_SESSION['a']["Maison"]==0 ){
	    	// Joueur2 Gagne
	    	return "\Fin.html";
	    }else{
	    	// partie non finis
	    	return "\Jouer.html";	/* Permet d'eviter la réinitialisations des variables */
	    }
	}

	function verifInscription(){
		$bdd = new BDD();
		$message = "";
		$return = "\Inscription.html";

        if (isset($_POST['identifiant']) && $_POST['identifiant']!= '') {	#On verifie si l'utilisateur a saisi un identifiant
            if (isset($_POST['passwd']) && $_POST['passwd']!='') {			#On verifie si l'utilisateur a saisi un mot de passe
                $bdd->AddUser($_POST['identifiant'], $_POST['passwd']);
                $return = "\Connexion.html";
            }else $message="ErreurMDP";    
        }else $message="ErreurId";

        if ($message=="ErreurMDP") {
	        echo "Vous n'avez pas saisi de mot de passe";
	    }
	    if ($message=="ErreurId") {
	        echo "Vous n'avez pas saisi d'dientifiant";
	    }
	    if ($message=="VerifOK") {
	        echo "Il n'y a pas de doublon d'utilsateur";
	    }

	    return $return;
	}

	function validerConnexion(){
		$bdd = new BDD();
		$message = "";
		$return="\Connexion.html"; 

        if (isset($_POST['identifiant']) && $_POST['identifiant'] != '' ) {
        #Si l'utilisateur à bien rentré un identifiant

            if (isset($_POST['passwd']) && $_POST['passwd'] != '' ) {
            #Si l'utilisateur à bien saisi son mot de passe
                if($bdd->logIn($_POST['identifiant'], $_POST['passwd'])){
                	$return = "\Accueil.html";
                }
                
            }else  $message="ErreurMDP";
        }else  $message="ErreurId";

        return $return;
	}

 ?>