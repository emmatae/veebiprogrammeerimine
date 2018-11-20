<?php
  require("functions.php");
  //lisame klassi
  require("classes/Photoupload.class.php");
  
  //kui pole sisselogitud
  if(!isset($_SESSION["userId"])){
	header("Location: index_1.php");
    exit();	
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location:  index_1.php");
	exit();
  }
  
  //pildi üleslaadimise osa
    $notice = "";
	$target_dir = "../vp_pic_uploads/";
	$target_file = "";
	$uploadOk = 1;
	//$imageFileType = "";
	$imageNamePrefix = "vp_";
    $textToImage = "Veebiprogrammeerimine";
    $pathToWatermark = "../vp_picfiles/vp_logo_w100_overlay.png";
	
	//kas vajutati submit nuppu
	if(isset($_POST["submitPic"])) {
		//var_dump($_POST);
		//var_dump($_FILES);
		//kas failinimi ka olemas on
		if(!empty($_FILES["fileToUpload"]["name"])){
		
			//$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"]);
            $myPhoto = new Photoupload($_FILES["fileToUpload"]);
			
			$myPhoto->makeFileName($imageNamePrefix);
			//määrame faili nime
			$target_file = $target_dir .$myPhoto->fileName;
			
			//kas on pilt
			$uploadOk = $myPhoto->checkForImage();
			if($uploadOk == 1){
			  // kas on sobiv tüüp
			  $uploadOk = $myPhoto->checkForFileType();
			}
			
			if($uploadOk == 1){
			  // kas on sobiv suurus
			  $uploadOk = $myPhoto->checkForFileSize($_FILES["fileToUpload"], 2500000);
			}
			
			if($uploadOk == 1){
			  // kas on juba olemas
			  $uploadOk = $myPhoto->checkIfExists($target_file);
			}
						
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				$notice = "Vabandame, faili ei laetud üles! Tekkisid vead: ".$myPhoto->errorsForUpload;
			// kui kõik korras, laeme üles
			} else {
				
				$myPhoto->resizeImage(600, 400);
				$myPhoto->addWatermark($pathToWatermark);
				$myPhoto->addText($textToImage);
				$saveResult = $myPhoto->savePhoto($target_file);
				//kui salvestus õnnestus, lisame andmebaasi
				if($saveResult == 1){
				  $notice = "Foto laeti üles! ";
				  $notice .= addPhotoData($myPhoto->fileName, $_POST["altText"], $_POST["privacy"]);
				} else {
                  $notice .= "Foto lisamisel andmebaasi tekkis viga!";
                }
				
			}
			unset($myPhoto);
		}//ega failinimi tühi pole
	}//kas on submit nuppu vajutatud
  
  //lehe päise laadimise osa
  $pageTitle = "Fotode üleslaadimine";
  require("header.php");
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pildifail (soovitavalt mahuga kuni 2,5MB):</label><br><br>
	<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
	<label>Alt tekst: </label>
	<input type="text" name="altText">
	<br><br>
	<label>Määra pildi kasutusõigused</label>
	<br>
	<input type="radio" name="privacy" value="1"><label> Avalik pilt</label>
	<input type="radio" name="privacy" value="2"><label> Ainult sisseloginud kasutajatele</label>
	<input type="radio" name="privacy" value="3" checked><label> Privaatne</label>
	<br><br>
    <input type="submit" value="Lae pilt üles" name="submitImage">
	<hr>
	<ul>
      <li>Tagasi <a href="main.php">pealehele</a>.</li>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	</ul>
</form>
	
  </body>
</html>