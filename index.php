<?php 
	
	session_start();


	/******************************************************** TRAITEMENT ************************************************************/


	if(isset($_GET['Jouer'])){
		$AFFICHAGE="\Nvo_citoyen.html";
	}
	else if(isset($_GET['Chasseur'])){
		$AFFICHAGE="\Action-Chasseur.html";
	}
	else if(isset($_GET['Constructeur'])){
		$AFFICHAGE="\Action-Constructeur.html";

		/* En fonction de l'action des chasseurs */
		if ($_GET['Constructeur']=="chasser") {
			$_SESSION["Nourriture"] += 10;
		}else if($_GET['Constructeur']=="bétail"){
			$_SESSION["Nourriture"] += 5;
		}
	}
	else if(isset($_GET['Assassin'])){
		$AFFICHAGE="\Action-Assassin.html";

		/* En fonction de l'action des constructeurs */
		if ($_GET['Assassin']=="maison") {
			$_SESSION["Maison"]+=1;
		}else if($_GET['Assassin']=="tour"){
			$_SESSION["Tour"]+=1;
		}
	}
	else if(isset($_GET['Resume'])){
		/* Permet d'eviter la réinitialisations des variables */
		$AFFICHAGE="\Accueil.html";

		// TODO TOUR DE L'ORDI ICI

		/* En fonction de l'action des assassins */
		if ($_GET['Resume']=="citoyen") {
			$_SESSION["Ordi_Chasseur"]-=1;
		}else if($_GET['Resume']=="maison"){
			$_SESSION["Ordi_Maison"]-=1;
		}
	}
	else{
		$AFFICHAGE="\Accueil.html";

		/* INIT QUE SUR LA PAGE D'ACCUEIL*/
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
	}
	/******************************************************** AFFICHAGE ************************************************************/
	include("..\Projet_Slam1\Vue\Commun\Top-page.html");
	include("..\Projet_Slam1\Vue\Commun\Header.html");
	include("..\Projet_Slam1\Vue".$AFFICHAGE);
	include("..\Projet_Slam1\Vue\Commun\Footer.html");
	include("..\Projet_Slam1\Vue\Commun\Bottom-page.html");
	
 ?>