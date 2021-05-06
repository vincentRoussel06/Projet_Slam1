<?php 
	
	session_start();
	
	#connexion a BDD 
	try {
	    $bdd =  new PDO('mysql:host=localhost;dbname=Projet_Slam1;charset=utf8','root','');
	} catch (Exception $e) {
	    die('Erreur : '.$e->getMessage());
	}
	#fin co

/*	#req 1 CATEG
	$requete = 'SELECT * FROM user';
	$req = $bdd->prepare($requete);
	$req->execute();
	$donnee = array(); // tab pour les donnee
	$cpt=0;
	while($data = $req->fetch()){	// init le TAB avec la bdd
		$donnee[$cpt]['id'] = $data['id'];
		$donnee[$cpt]['Mail'] = $data['Mail'];
		$donnee[$cpt]['Mdp'] = $data['Mdp'];
		$donnee[$cpt]['Partie'] = $data['Partie'];
		$donnee[$cpt]['NbTour'] = $data['NbTour'];
		$cpt++;
	}
	#fin req1
	foreach ($donnee as $tab) {
		foreach ($tab as $key => $value) {
			echo $key." : ".$value." | ";
		}
		echo "<br>";
	}*/

	/******************************************************** TRAITEMENT ************************************************************/

	// determine la page à afficher en fonction du param GET envoyer
	if(isset($_GET['Jouer'])){
		$AFFICHAGE=initPartie();
	}
	else if(isset($_GET['Citoyen'])){
		$AFFICHAGE="\Nvo_citoyen.html";
	}
	else if(isset($_GET['Chasseur'])){
		// Affectation des nouveaux citoyen au village
		// Les valeurs sont envoyés grace au formulaire de nvoCitoyen.html
		$_SESSION["Chasseur"] = $_POST["resChasseur"];
		$_SESSION["Constructeur"] = $_POST["resConstructeur"];
		$_SESSION["Assassin"] = $_POST["resAssassin"];
		$AFFICHAGE="\Action-Chasseur.html";
	}
	else if(isset($_GET['Constructeur'])){
		$AFFICHAGE = actionChasseur();
	}
	else if(isset($_GET['Assassin'])){
		$AFFICHAGE = actionConstructeur();
	}
	else if(isset($_GET['Resume'])){
		$AFFICHAGE = actionAssassin();

		// changeent de joueur
		$_SESSION['joueur'] = $_SESSION['joueur']==1 ? 2 : 1;
	}
	else{
		$AFFICHAGE="\Accueil.html";
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
		$_SESSION["Joueur1"]["Piege"] = 0;
		$_SESSION["Joueur1"]["Maison"] = 1;
		$_SESSION["Joueur1"]["Chasseur"] = 1;
		$_SESSION["Joueur1"]["Constructeur"] = 1;
		$_SESSION["Joueur1"]["Assassin"] = 1;
		$_SESSION["Joueur1"]["Nourriture"] = 100;

		$_SESSION["Joueur2"]["Piege"] = 2;
		$_SESSION["Joueur2"]["Maison"] = 2;
		$_SESSION["Joueur2"]["Chasseur"] = 2;
		$_SESSION["Joueur2"]["Constructeur"] = 2;
		$_SESSION["Joueur2"]["Assassin"] = 2;
		$_SESSION["Joueur2"]["Nourriture"] = 200;

		$_SESSION["joueur"] = 1; // Change de joueur à la fin de chaque tour

		$AFFICHAGE="\Jouer.html";	

		return "/Jouer.html";
	}

	function actionChasseur(){
		$AFFICHAGE="\Action-Constructeur.html";

		/* En fonction de l'action des chasseurs */
		if ($_GET['Constructeur']=="chasser") {
			$_SESSION["Nourriture"] += (10*$_SESSION["Chasseur"]); // ajoute 10 de nourriture par chasseur
		}else if($_GET['Constructeur']=="vol"){
			if(rand(0,1)==1){
				echo "mort!!!!!!!!!!";
				// TODO faire mourir la moitier des chasseurs
			}
			$_SESSION["Nourriture"] += (5*$_SESSION["Chasseur"]);	// ajoute 5 de nourriture par chasseur
		}

		return "\Action-Constructeur.html";
	}

	function actionConstructeur(){
		/* En fonction de l'action des constructeurs */
		if ($_GET['Assassin']=="maison") {
			$_SESSION["Maison"] += (1*$_SESSION["Constructeur"]);	// Construit 1 maison par constructeurs
		}else if($_GET['Assassin']=="tour"){
			$_SESSION["Piege"] += (1*$_SESSION["Constructeur"]);		// Construit 1 piege par constructeurs
		}

		return "\Action-Assassin.html";
	}

	function actionAssassin(){
		/* En fonction de l'action des assassins */
		if ($_GET['Resume']=="citoyen") {
			assassinTueCitoyens();
		}else if($_GET['Resume']=="maison"){
			assassinDetruitMaison();
		}

		return verifFinPartie();
	}

	function assassinTueCitoyens(){
		// Tue d'abord les Chasseur, puis les constructeurs et enfin les assassins
		for ($i = 0; $i < $_SESSION["Assassin"]-$_SESSION["Ordi_Piege"]; $i++) {
			if($_SESSION["Ordi_Chasseur"]>0){
				$_SESSION["Ordi_Chasseur"] -= 1;		// Tue 1 citoyen par assassin
			}else if ($_SESSION["Ordi_Constructeur"]>0){
				$_SESSION["Ordi_Constructeur"] -= 1;	// Tue 1 citoyen par assassin
			}else if($_SESSION["Ordi_Assassin"]>0){
				$_SESSION["Ordi_Assassin"] -= 1;		// Tue 1 citoyen par assassin
			}
		}
	}

	function assassinDetruitMaison(){
		// baisse au max jusqu'à 0 le nb de maison
		for ($i = 0; $i <= $_SESSION["Chasseur"]-$_SESSION["Ordi_Piege"]; $i++) {
			if($_SESSION["Ordi_Maison"]>0){
				$_SESSION["Ordi_Maison"] -= 1;	
			}
		}
	}

	function verifFinPartie(){
	    if ($_SESSION[2]["Assassin"]==0 && $_SESSION[2]["Constructeur"]==0 && $_SESSION[2]["Chasseur"]==0 && $_SESSION[2]["Maison"]==0 ){
	    	// Joueur1 Gagne
	    	return "\Fin.html";
	    }else if ($_SESSION[1]["Assassin"]==0 && $_SESSION[1]["Constructeur"]==0 && $_SESSION[1]["Chasseur"]==0 && $_SESSION[1]["Maison"]==0 ){
	    	// Joueur2 Gagne
	    	return "\Fin.html";
	    }else{
	    	// partie non finis
	    	return "\Jouer.html";	/* Permet d'eviter la réinitialisations des variables */
	    }
	}
 ?>