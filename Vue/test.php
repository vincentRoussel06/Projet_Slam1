<?php 
session_start();
$_SESSION["Chasseur"]=0;
if(isset($_POST["resChasseur"])){
  echo "ok : ".$_POST["resChasseur"].";";
  $_SESSION["Chasseur"] = $_POST["resChasseur"];
}else{
  echo "Nonok";
  $_SESSION["Chasseur"]=10; 
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Test btn javascript</title>
  </head>
  <body >
    <form action="test.php" method="POST">
      <p>Nombre de Chasseur : </p>
      <!-- Le output n'est pas envoyer -->
      <output id="NewChasseur"><?= $_SESSION["Chasseur"];?></output><br> 
      <input type="button" value="+" onclick="NewChasseur.value = parseInt(NewChasseur.value)+1;">
      <input type="button" value="-" onclick="NewChasseur.value = parseInt(NewChasseur.value)-1;">

      <!-- Donc créer un input cacher qui renvoie la valeur de l'ouput lors de la validation  -->
      <input type="hidden" id="resChasseurId" name="resChasseur">
      <input type="submit" name="submit" onclick="resChasseurId.value = NewChasseur.value"> <!-- onsubmit don't work  -->
    </form> 

    <br><br>
    <button style="display: block; margin: auto; padding: 1%;"><a href="../index.php?Accueil">Retour </a></button>
    <br><br>
  </body>
</html>