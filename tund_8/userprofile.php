<?php
//laen andmebaasi info
  require("../../../config.php");
  
  require("functions.php");
  
  //echo $GLOBALS["serverUsername"];
  $database = "if18_emma_tae_1";
  
  //võtan kasutusele sessiooni

  $mydescription="Sisu ";
  
  $description= "";
  $bgcolor= "";
  $txtcolor= "";
  $notice= "";

  if(isset($_POST["submitProfile"])){
    if(isset($_POST["description"])){
      if(empty($_POST["description"])){
        $description="Pole iseloomustust lisanud";
    } else {
      $description=test_input($_POST["description"]);
    }  
    }
    if(isset($_POST["bgcolor"])){
      $bgcolor = $_POST["bgcolor"];
    }
    if(isset($_POST["txtcolor"])){
      $txtcolor= $_POST["txtcolor"];
    }
    if(empty($description)){
      $notice = saveprofile($description, $bgcolor, $txtcolor);
    }
  }
$data= userprofileload();
  
  //kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	  header("Location: index_1.php");
	  exit();
  }
  //välja logimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index_1.php");
	exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kasutaja profiil</title>
  
  <?php
  echo "<style>
    body{
      background-color: " .$data[1] .";
      color: " .$data[2] ."
      }
  </style>";
  ?>
  
</head>

<body>
 <h1>Profiil</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p><hr>
    <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <form>
  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $data[1]; ?>"><br>
  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $data[2]; ?>"><br>
  <input name="submitUserData" type="submit" value="Salvesta profiil">
  </form>
  
</body>

</html>