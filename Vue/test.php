<?php echo"bonjour"; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Changer le texte d'un bouton avec jQuery</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
      var chass = 0;
      function initElement(){
    	  var plus = document.getElementById("btn+");
    	  plus.onclick = add;

    	  var moin = document.getElementById("btn-");
    	  moin.onclick = less;
    	};

    	function add(){
        chass = chass+1;      
        $("#chass").html("Nombre de Chasseur : "+chass );
      }

      function less(){ 
        chass = chass-1;  
        $("#chass").html("Nombre de Chasseur : "+chass );
      }

    </script>
  </head>
  <body onload="initElement();">

  	<p id="chass"> Nombre de Chasseur : 0; ?> </p>

    <button type="button" id="btn+">+</button>
	  <button type="button" id="btn-">-</button>

    <br><br>
    <button style="display: block; margin: auto; padding: 1%;"><a href="index.php?Accueil">Suivant</a></button>
    <br><br>
  </body>
</html>

