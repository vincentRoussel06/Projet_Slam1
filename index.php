<?php 
	session_start();
	require_once('bdd.php');

	// TODO :
	// afficher score et meilleurs score
	// faire action nourriture
	// enregistrer les tourszs

	#connexion a BDD 
	$bdd = new BDD();

	/********************************************* TRAITEMENT *****************************************************/

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

		// Change de joueur à la fin de chaque tour et incremente les tours
		if ($_SESSION['joueur']=='a'){
			$_SESSION['joueur']='b';	
		}else{
			$_SESSION['joueur']='a';
			$_SESSION['nbTour']+=1;
		} 
	}
	/********************************** SCORE / BESTSCORE **********************************/
	else if(isset($_GET['score'])){
		$_SESSION["Score"] = $bdd->printGameByUser($_SESSION["Name"]);
		$AFFICHAGE = "\Score.html";
	}
	else if(isset($_GET['bestScore'])){
		$_SESSION["bestScore"] = $bdd->printBestGame();
		$AFFICHAGE = "\BestScore.html";
	}
	/********************************** CONNEXION / INSCRIPTION **********************************/
	else if (isset($_GET['deco'])) {		// deco l'util et retourne à l'accueil
        session_destroy();
        header("Location: index.php");   
    }
    else if (isset($_GET['connexion'])) {	// affiche page 'connexion'
        $AFFICHAGE = "\Connexion.html";   
    }
    else if (isset($_GET['inscription'])) {	// affiche page 'inscription'
        $AFFICHAGE = "\Inscription.html";   
    }
    else if (isset($_GET['validerConnexion'])) { 		// Si l'utilisateur appuie sur le bouton  
    	$AFFICHAGE = validerConnexion();
    }
    else if (isset($_GET['ValiderInscription'])) {		// On verifie si l'utilisateur à valider le formulaire
 		$AFFICHAGE = verifInscription();
    }
	else{
		$AFFICHAGE = "\Accueil.html";
	}

	/********************************************** AFFICHAGE *******************************************************/

	include("..\Projet_Slam1\Vue\Commun\Top-page.html");
	include("..\Projet_Slam1\Vue\Commun\Header.html");
	include("..\Projet_Slam1\Vue".$AFFICHAGE);
	include("..\Projet_Slam1\Vue\Commun\Footer.html");
	include("..\Projet_Slam1\Vue\Commun\Bottom-page.html");

	/***************************************** FONCTION *************************************************/

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
		$_SESSION["nbTour"] = 1; 		// compteur du nb tours
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
			// Construit 1 maison par constructeurs
			$_SESSION[$Joueur]["Maison"] += $_SESSION[$Joueur]["Constructeur"];
			// enlève 10 nourriture par action	
			$_SESSION[$Joueur]["Nourriture"] -= (10*$_SESSION[$Joueur]["Constructeur"]); 
		}else if($_GET['Assassin']=="tour"){
			// Construit 1 piege par constructeurs
			$_SESSION[$Joueur]["Piege"] += $_SESSION[$Joueur]["Constructeur"];
			// enlève 10 nourriture par action		
			$_SESSION[$Joueur]["Nourriture"] -= (10*$_SESSION[$Joueur]["Constructeur"]); 
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
		$nbTotalAction = $_SESSION[$Joueur]["Assassin"]-$_SESSION[$Enemi]["Piege"];
		for ($i = 0; $i < $nbTotalAction ; $i++) {	// s'execute en fonction du nombre d'assasin dispo
			if($_SESSION[$Enemi]["Chasseur"]>0){
				$_SESSION[$Enemi]["Chasseur"] -= 1;		// Tue 1 citoyen par assassin
				$_SESSION[$Joueur]["Nourriture"] -= 10;	// enlève 10 nourriture par action
			}else if ($_SESSION[$Enemi]["Constructeur"]>0){
				$_SESSION[$Enemi]["Constructeur"] -= 1;	// Tue 1 citoyen par assassin
				$_SESSION[$Joueur]["Nourriture"] -= 10;	// enlève 10 nourriture par action
			}else if($_SESSION[$Enemi]["Assassin"]>0){
				$_SESSION[$Enemi]["Assassin"] -= 1;		// Tue 1 citoyen par assassin
				$_SESSION[$Joueur]["Nourriture"] -= 10;	// enlève 10 nourriture par action
			}else if($_SESSION[$Enemi]["Maison"]>0){
				$_SESSION[$Enemi]["Maison"] -= 1;		// detruit 1 maison par assassin
				$_SESSION[$Joueur]["Nourriture"] -= 10;	// enlève 10 nourriture par action
			}
		}
	}

	function assassinDetruitMaison($Joueur, $Enemi){
		// baisse au max jusqu'à 0 le nb de maison
		$nbTotalAction = $_SESSION[$Joueur]["Assassin"]-$_SESSION[$Enemi]["Piege"];
		for ($i = 0; $i < $nbTotalAction ; $i++) {	// s'execute en fonction du nombre d'assasin dispo
			if($_SESSION[$Enemi]["Maison"]>0){
				$_SESSION[$Enemi]["Maison"] -= 1;
				$_SESSION[$Joueur]["Nourriture"] -= 10;	// enlève 10 nourriture par action	
			}else{
				$assassinEnTrop = $nbTotalAction-$i;
			}
		}

		// n'est fait que ici pour : eviter une boucle infernale lorsque le joueur a trop d'assasin par rapport au nb total des maisons et des citoyens du joueur adverse.
		if (isset($assassinEnTrop) && $assassinEnTrop>0 ){
			assassinTueCitoyens($Joueur, $Enemi);
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
		$bdd = new BDD();

	    if ($_SESSION['b']["Assassin"]==0 && $_SESSION['b']["Constructeur"]==0 && $_SESSION['b']["Chasseur"]==0 && $_SESSION['b']["Maison"]==0 ){
	    	// Joueur1 Gagne

	    	// attention au format de la date
			$bdd->AddGame($_SESSION["Name"],NULL, $_SESSION["nbTour"], date("Y-m-d"), 1);
	    	return "\Fin.html";
	    }else if ($_SESSION['a']["Assassin"]==0 && $_SESSION['a']["Constructeur"]==0 && $_SESSION['a']["Chasseur"]==0 && $_SESSION['a']["Maison"]==0 ){
	    	// Joueur1 Perd

	    	// attention au format de la date
	    	$bdd->AddGame($_SESSION["Name"],NULL, $_SESSION["nbTour"], date("Y-m-d"), 0);
	    	return "\Fin.html";
	    }else{
	    	// partie non finis

	    	return "\Jouer.html";	/* Permet d'eviter la réinitialisations des variables */
	    }
	}

	function verifInscription(){
		$bdd = new BDD();
		$return = "\Inscription.html";

        if (isset($_POST['identifiant']) && $_POST['identifiant']!= '') {	#On verifie si l'utilisateur a saisi un identifiant
            if (isset($_POST['passwd']) && $_POST['passwd']!='') {			#On verifie si l'utilisateur a saisi un mot de passe
                $bdd->AddUser($_POST['identifiant'], $_POST['passwd']);
                echo '<script type="text/javascript"> alert("Inscription validée");</script>';
                $return = "\Connexion.html";
            }   
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
                }else{
                	echo '<script type="text/javascript"> alert("Mauvais identifiant ou mdp");</script>';
                }
            }
        }

        return $return;
	}

 ?>