<?php
  require("functions.php");
  
  //kui pole sisse loginud, siis logimise lehele
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
  //piltide laadimise osa
	$target_dir = "../vp_pic_uploads/";
	$uploadOk = 1;
	
	// Kontrollime, kas tegemist on pildiga või mitte
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			//var_dump($_FILES["fileToUpload"]["name"]);
		    //echo $_FILES["fileToUpload"]["fileName"];
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			
			$target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir .$target_file_name;
			
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail on " . $check["mime"] . " pilt.";
				//$uploadOk = 1;
			} else {
				echo "Fail pole pilt!";
				$uploadOk = 0;
			}
			
			// Kontrollime, kas fail juba eksisteerib
			if (file_exists($target_file)) {
				echo "Selle nimega fail on juba olemas!";
				$uploadOk = 0;
			}
			// Kontrollime faili suurust
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Pilt on liiga suur!";
				$uploadOk = 0;
			}
			
			// Ainult teatud vormid lubatud
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Vabandage, ainult JPG, JPEG, PNG ja GIF failid on lubatud!";
				$uploadOk = 0;
			}
			
			// Kui tuleb error
			if ($uploadOk == 0) {
				echo "Valitud faili ei saa üles laadida!";
			// Kui kõik on korras
			} else {
				//sõltuvalt failitüübist loon sobiva pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//pildi originaalsuurus
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//leian suuruse muutmise suhtarvu
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / 600;
				} else {
					$sizeRatio = $imageHeight / 400;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);
				
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//vesimärk 
				$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
				$waterMarkWidth = imagesx($waterMark);
				$waterMarkHeight = imagesy($waterMark);
				$waterMarkPosX = $newWidth - $waterMarkWidth - 10;
				$waterMarkPosY = $newHeight - $waterMarkHeight - 10;
				imagecopy($myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
				
				//tekst vesimärgina
				$textToImage = "Veebiprogrammeerimine";
				//Järjestus- Mis pilt, R(red), G(green), B(blue), ALPHA 0...127
				$textColor = imagecolorallocatealpha($myImage, 255, 255, 255, 60);
				imagettftext($myImage, 20, 0, 10, 30, $textColor, "../vp_picfiles/ARIALBD.TTF",$textToImage);
				
				//faili salvestamine,jälle sõltuvalt failitüübist
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 90)){
						echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
					}
				}
				
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 6)){
						echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
					}
				}
				
				if($imageFileType == "gif"){
					if(imagegif($myImage, $target_file)){
						echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
						addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
					} else {
						echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
					}
				}
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				imagedestroy($waterMark);
				
				/* if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles!";
				} else {
					echo "Vabandame, faili üleslaadimisel tekkis tehniline viga!";
				} */
			}
		}//if !empty lõppeb
	}//siin lõppeb nupuvajutuse kontroll
	
	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	}
	
  //lehe päise laadimine
  $pageTitle = "Fotode üleslaadimine";
  require("header.php");
  
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] .".";?></p>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pildifail (soovitavalt mahuga kuni 2,5MB):</label><br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
	<label>Alt tekst: </label>
	<input type="text" name="altText">
	<br>
	<label>Määra pildi kasutusõigused</label>
	<br>
	<input type="radio" name="privacy" value="1"><label> Avalik pilt</label>
	<input type="radio" name="privacy" value="2"><label> Ainult sisselogitud kasutajatele</label>
	<input type="radio" name="privacy" value="3" checked><label> Privaatne</label>
	<br><br>
    <input type="submit" value="Lae pilt üles" name="submitImage"><span>
	<hr>
	
	<ul>
      <li><a href="?logout=1">Logi välja.</a></li> 
	  <li><a href="main.php">Tagasi pealehele</a>.</li>
	</ul>
	
</form>
	
  </body>
</html>