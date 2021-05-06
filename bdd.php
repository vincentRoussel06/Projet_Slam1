
<?php

#On créer la class BDD
class BDD{
	private $bdd;
	#Constructeur
	public function __construct(){
		#Connexion a la BDD
		try {
		    $this->bdd =  new PDO('mysql:host=localhost;dbname=Projet_Slam1;charset=utf8','root','');
		} catch (Exception $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	#Methodes
	function logIn($nomJoueur, $motDePasse){
		#requete qui récup les données du joueur dans la table 'joueur' dans roulette
		$req='SELECT *
			  FROM user
			  WHERE Nom=?';
		$reponse = $this->bdd->prepare($req);
		$reponse->execute(array($nomJoueur));

		if($reponse->rowcount()==0){
			echo '<script type="text/javascript"> alert("Verifier vos identifiants et votre mot de passe");</script>';
			return false;
		}

		while ($donnees = $reponse->fetch()) {
			if (password_verify($motDePasse, $donnees['Mdp'])) {
				$_SESSION["Name"]=$donnees["Nom"];
				return true;
			}
		}
	}


	function AddUser($identifiant,$passwd){
		#methode d'ajout d'utilisateur
		$req="INSERT INTO user (Nom, Mdp, Partie, NbTour) 
			  VALUES (?, ?, ?, ?)";
		$reponse= $this->bdd->prepare($req);
		$mdp = password_hash($passwd, PASSWORD_DEFAULT);
		$reponse->execute(array($identifiant,$mdp,0,0));
		echo '<script type="text/javascript"> alert("Insertion validée");</script>';

	}

	function AddGame($IDJoueur,$DatePartie, $MiseJoueur, $GainJoueur){
		$req="INSERT INTO parties (IDJoueur,DatePartie, MiseJoueur, GainJoueur)
			  VALUES (?, ? ,? ,?)";
		$reponse= $this->bdd->prepare($req);
		$reponse->execute(array($IDJoueur, $DatePartie, $MiseJoueur, $GainJoueur));
	}

	function printAllUser(){
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
		
		foreach ($donnee as $tab) {
			foreach ($tab as $key => $value) {
				echo $key." : ".$value." | ";
			}
			echo "<br>";
		}
	}

	#ToString


}