<?php 
	
	session_start();


	/******************************************************** TRAITEMENT ************************************************************/

	// determine la page à afficher en fonction du param GET envoyer
	if(isset($_GET['Jouer'])){
		initPartie();
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
		$AFFICHAGE="\Action-Constructeur.html";

		/* En fonction de l'action des chasseurs */
		if ($_GET['Constructeur']=="chasser") {
			$_SESSION["Nourriture"] += (10*$_SESSION["Chasseur"]); // ajoute 10 de nourriture par chasseur
		}else if($_GET['Constructeur']=="bétail"){
			$_SESSION["Nourriture"] += (5*$_SESSION["Chasseur"]);	// ajoute 5 de nourriture par chasseur
		}
	}
	else if(isset($_GET['Assassin'])){
		$AFFICHAGE="\Action-Assassin.html";

		/* En fonction de l'action des constructeurs */
		if ($_GET['Assassin']=="maison") {
			$_SESSION["Maison"] += (1*$_SESSION["Constructeur"]);	// Construit 1 maison par constructeurs
		}else if($_GET['Assassin']=="tour"){
			$_SESSION["Tour"] += (1*$_SESSION["Constructeur"]);		// Construit 1 tour par constructeurs
		}
	}
	else if(isset($_GET['Resume'])){

		/* En fonction de l'action des assassins */
		if ($_GET['Resume']=="citoyen") {
			assassinTueCitoyens()
		}else if($_GET['Resume']=="maison"){
			assassinDetruitMaison();
		}

		$AFFICHAGE=verifFinPartie();
		// TODO TOUR DE L'ORDI ICI
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
	function verifFinPartie(){
	    if ($_SESSION["Ordi_Assassin"]==0 && $_SESSION["Ordi_Constructeur"]==0 && $_SESSION["Ordi_Chasseur"]==0 && $_SESSION["Ordi_Maison"]==0 ){
	    	// partie Gagner
	    	return "\Fin.html";
	    }else if ($_SESSION["Assassin"]==0 && $_SESSION["Constructeur"]==0 && $_SESSION["Chasseur"]==0 && $_SESSION["Maison"]==0 ){
	    	// partie Perdu
	    	return "\Fin.html";
	    }else{
	    	// partie non finis
	    	return "\Jouer.html";	/* Permet d'eviter la réinitialisations des variables */
	    }
	}

	function assassinTueCitoyens(){
		// Tue d'abord les Chasseur, puis les constructeurs et enfin les assassins
		for ($i = 0; $i < $_SESSION["Assassin"]; $i++) {
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
		for ($i = 0; $i <= $_SESSION["Chasseur"]; $i++) {
			if($_SESSION["Ordi_Maison"]>0){
				$_SESSION["Ordi_Maison"] -= 1;	
			}
		}
	}

	function initPartie(){
		/* INIT A CHAQUE DEBUT DE PARTIE*/
		$_SESSION["Tour"] = 0;
		$_SESSION["Maison"] = 1;
		$_SESSION["Chasseur"] = 1;
		$_SESSION["Constructeur"] = 1;
		$_SESSION["Assassin"] = 1;
		$_SESSION["Nourriture"] = 100;

		$_SESSION["Ordi_Tour"] = 0;
		$_SESSION["Ordi_Maison"] = 1;
		$_SESSION["Ordi_Chasseur"] = 2;
		$_SESSION["Ordi_Constructeur"] = 2;
		$_SESSION["Ordi_Assassin"] = 2;
		$_SESSION["Ordi_Nourriture"] = 100;
		$AFFICHAGE="\Jouer.html";	
	}
 ?>