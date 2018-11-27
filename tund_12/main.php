<?php
  require("functions.php");
  
  //kui pole sisse loginud, siis logimise lehele
  if(!isset($_SESSION["userId"])){
	  header("Location: index_1.php");
	  exit();
  }
  
  //ul'i ja li vahel on välja logimine
  //logime välja
  if(isset($_GET["logout"])){
	  session_destroy();
	  header("Location: index_1.php");
	  exit();
  }
  
  $pageTitle = "Pealeht";
  require("header.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title></title>
	<style>
	  <?php
        echo "body{background-color: " .$_SESSION["bgColor"] ."; \n";
		echo "color: " .$_SESSION["txtColor"] ."} \n";
	  ?>
	</style>
  </head>
  <body>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] .".";?></p>
	<ul>
	  <li>Minu <a href="userprofile.php">kasutajaprofiil</a>.</li>
	  <li><a href="validatemsg.php">Valideeri anonüümseid sõnumeid.</a></li>
	  <li>Süsteemi <a href="users.php">kasutajad</a>.</li>
	  <li>Näita valideeritud <a href="validatedmessages.php">sõnumeid</a> valideerijate kaupa!</li>
	  <li>Fotode <a href="photoupload.php">üleslaadimine</a>.</li>
	  <li>Avalike fotode <a href="pubgallery.php">galerii</a>.</li>
	  <li>Minu fotode <a href="mygallery.php">galerii</a>.</li><br><br>
	  <li><a href="?logout=1">Logi välja.</a></li> 
	</ul>
	
  </body>
</html>