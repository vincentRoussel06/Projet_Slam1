
<?php

#On créer la class BDD
class BDD{
	private $bdd;
	
	/****************************************** Constructeur **********************************************/
	public function __construct(){
		#Connexion a la BDD
		try {
		    $this->bdd =  new PDO('mysql:host=localhost;dbname=projet_Slam1;charset=utf8','root','');
		} catch (Exception $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	/********************************************* FONCTION **************************************************/
	function logIn($nomJoueur, $motDePasse){
		#requete qui récup les données du joueur dans la table 'user'
		$req='SELECT *
			  FROM user
			  WHERE Nom=?';
		$reponse = $this->bdd->prepare($req);
		$reponse->execute(array($nomJoueur));
		$areturn=false;

		while ($donnees = $reponse->fetch()) {
			/*if (password_verify($motDePasse, $donnees['Mdp'])) {
				$_SESSION["Name"]=$donnees["Nom"];
				$areturn = true;
			}*/

			if ($donnees['Mdp']==$motDePasse) {
				$_SESSION["Name"]=$donnees["Nom"];
				$areturn = true;
			}
		}
		return $areturn;
	}


	function AddUser($identifiant,$passwd){
		#methode d'ajout d'utilisateur
		$req="INSERT INTO user (Nom, Mdp, Partie, NbTour) 
			  VALUES (?, ?, ?, ?)";
		$reponse= $this->bdd->prepare($req);
		// $mdp = password_hash($passwd, PASSWORD_DEFAULT);
		$mdp = $passwd;
		$reponse->execute(array($identifiant,$mdp,0,0));
	}

	function AddGame($idJoueur, $idPartie, $nbTour, $datePartie, $resultat){
		$req = "INSERT INTO score (idJoueur, idPartie, nbTour, datePartie, resultat) VALUES (?, ? ,? ,?, ?)";
		$reponse= $this->bdd->prepare($req);
		$reponse->execute(array($idJoueur, $idPartie, $nbTour, $datePartie, $resultat));
	}

	function printGameByUser($user){
		$requete = 'SELECT * FROM score WHERE idJoueur=?';
		$req = $this->bdd->prepare($requete);
		$req->execute(array($user));
		$donnee = array(); // tab pour les donnee
		$cpt=0;

		while($data = $req->fetch()){	// init le TAB avec la bdd
			$donnee[$cpt]['idJoueur'] = $data['idJoueur'];
			$donnee[$cpt]['idPartie'] = $data['idPartie'];
			$donnee[$cpt]['nbTour'] = $data['nbTour'];
			$donnee[$cpt]['datePartie'] = $data['datePartie'];
			$donnee[$cpt]['resultat'] = $data['resultat'];
			$cpt++;
		}
		
		return $donnee;
	}

	function printBestGame(){
		$requete = 'SELECT * FROM score ORDER BY nbTour ASC';
		$req = $this->bdd->prepare($requete);
		$req->execute();
		$donnee = array(); // tab pour les donnee
		$cpt=0;

		while($data = $req->fetch()){	// init le TAB avec la bdd
			$donnee[$cpt]['idJoueur'] = $data['idJoueur'];
			$donnee[$cpt]['idPartie'] = $data['idPartie'];
			$donnee[$cpt]['nbTour'] = $data['nbTour'];
			$donnee[$cpt]['datePartie'] = $data['datePartie'];
			$donnee[$cpt]['resultat'] = $data['resultat'];
			$cpt++;
		}
		
		return $donnee;
	}

	#ToString


}