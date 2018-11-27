<?php
  require("functions.php");
  //kui pole sisseloginud, siis logimise lehele
  if(!isset($_SESSION["userId"])){
	header("Location: index_1.php");
	exit();  
  }
  
  //logime välja
  if(isset($_GET["logout"])){
	session_destroy();
    header("Location: index_1.php");
	exit();
  }
  
  $messages = readallunvalidatedmessages();
  
  $pageTitle = "Sõnumid";
  require("header.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
   <style>
	  <?php
        echo "body{background-color: " .$_SESSION["bgColor"] ."; \n";
		echo "color: " .$_SESSION["txtColor"] ."} \n";
	  ?>
	</style>
</head>
<body>
  <h1></h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <h2>Valideerimata sõnumid:</h2>
  <?php
    echo $messages;
  ?>
  <hr>
  <ul>
	<li><a href="main.php">Tagasi pealehele</a></li>
	<li><a href="?logout=1">Logi välja</a></li>
  </ul>
</body>
</html>