<?php 
	
	session_start();

	$_SESSION["Tour"] = 0;

	$_SESSION["Chasseur"] = 1;
	$_SESSION["Constructeur"] = 1;
	$_SESSION["Assassin"] = 1;
	$_SESSION["Nourriture"] = 100;

	$_SESSION["Ordi_Chasseur"] = 2;
	$_SESSION["Ordi_Constructeur"] = 2;
	$_SESSION["Ordi_Assassin"] = 2;
	$_SESSION["Ordi_Nourriture"] = 100;
	/******************************************************** TRAITEMENT ************************************************************/


	if(isset($_GET['Jouer'])){
		$AFFICHAGE="\Jouer.html";
	}else if(isset($_GET['Chasseur'])){
		$AFFICHAGE="\Action-Chasseur.html";
	}
	else if(isset($_GET['Constructeur'])){
		$AFFICHAGE="\Action-Constructeur.html";
	}
	else if(isset($_GET['Assassin'])){
		$AFFICHAGE="\Action-Assassin.html";
	}
	else if(isset($_GET['Resume'])){
		$AFFICHAGE="\ResumerGame.html";
	}
	else{
		$AFFICHAGE="\Accueil.html";
	}
	/******************************************************** AFFICHAGE ************************************************************/
	include("C:\wamp64\www\moi\Vue\Commun\Top-page.html");
	include("C:\wamp64\www\moi\Vue\Commun\Header.html");
	include("C:\wamp64\www\moi\Vue".$AFFICHAGE);
	include("C:\wamp64\www\moi\Vue\Commun\Footer.html");
	include("C:\wamp64\www\moi\Vue\Commun\Bottom-page.html");
	
	
 ?>