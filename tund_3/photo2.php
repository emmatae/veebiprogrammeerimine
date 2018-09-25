<?php
  //echo "See on minu esimene PHP!";
  $firstName = "Emma Loore";
  $lastName = "Tae";
  //loeme piltide kataloogi sisu
 $dirToRead = "../../pics/";
 $allFiles = scandir($dirToRead);
 $picFiles = array_slice ($allFiles, 2);
 //var_dump($picFiles);

?>

<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title>
     <?php
	    echo $firstName;
		echo " ";
		echo $lastName;
	 ?>
	 - Õppetöö</title>
</head>
<body>
   <h1>
     <?php
	    echo $firstName . " " . $lastName;
	 ?>, IF18
   </h1>
   <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames. Ei pruugi parim välja näha ning kindlasti ei sisalda tõsiseltvõetavat sisu!</p>
     
	 <?php
	//<img src="" alt="Tallinn">	
	  echo '<img src="' .$dirToRead .$picFiles[rand(0,count($picFiles) - 1)] .'" alt="pilt"><br>' . "\n";
	  
	 ?>
</body>
</html>