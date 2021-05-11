<?php 
session_start();
require_once('../bdd.php');
#connexion a BDD 
$bdd = new BDD();
$_SESSION["Chasseur"]=0;
if(isset($_POST["submit"])){
  $bdd->AddGame($_POST["Joueur"],NULL, $_POST["nbTour"], date("m.d.y"), true);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Test btn javascript</title>
  </head>
  <body >
    <form action="test.php" method="POST">
      <!-- Donc créer un input cacher qui renvoie la valeur de l'ouput lors de la validation  -->
      <input type="hidden" id="resChasseurId" name="resChasseur">
      <label for="tour">Nbtour :</label>
      <input type="number" name="nbTour" id="tour" value="20">
      <label for="Joueur">Joueur :</label>
      <input type="text" name="Joueur" id="Joueur">

      <input type="submit" name="submit" onclick="resChasseurId.value = NewChasseur.value" value="TestInsert"> <!-- onsubmit don't work  -->
    </form> 

    <br><br>
    <button style="display: block; margin: auto; padding: 1%;"><a href="../index.php?Accueil">Retour </a></button>
    <br><br>
  </body>
</html>