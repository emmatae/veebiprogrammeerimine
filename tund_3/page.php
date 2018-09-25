<?php
  //echo "See on minu esimene PHP!";
  $firstName = "Kodanik";
  $lastName = "Tundmatu";
  $fullName = "";
  $monthNamesET = [1 => "jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  $monthNow = date('n');
  //kontrollime, kas kasutaja on midagi kirjutanud
  //var_dump($_POST); 
  if (isset($_POST["firstName"])){
	  //$firstName = $_POST["firstName"];
	  $firstName = test_input($_POST["firstName"]);
  }
  if (isset($_POST["lastName"])){
	  $lastName = test_input($_POST["lastName"]);
  }
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }
  
  //täiesti mõttetu, harjutamiseks mõeldud funktsioon
  function fullName(){
	$GLOBALSfullName = $GLOBALS["firstName"] ." " .$GLOBALS["lastName"];  
  }
  
  
  fullName();
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
 
   <hr>

   <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
     <label>Eesnimi:</label>
	 <input type="text" name="firstName">
	 <label>Perekonnanimi:</label>
     <input type="text" name="lastName">
	 <label>Sünniaasta: </label>
	 <input type= "number" min="1914" max="2000" value="1999" name="birthYear">
     <br>
     <br>
	 <label>Sünnikuu:</label>
	 <select name="birthMonth">
	 <?php
	 foreach($monthNamesET as $key => $month)
	 {
		echo '<option value="'.$key.'" ';
		if ($key == $monthNow) {
			echo 'selected="selected"';
		}
		echo '>'.$month.'</option>'."\n";
		
		// Siin saab kasutada ka lühemat varianti (vt ternary operator)
		// {tingimus} ? {väärtus kui tõene} : {väärtus kui vale}
		//echo '<option value="'.$key.'" ' . ($key == $monthNow ? 'selected="selected"' : '') . '>'.$month.'</option>'."\n";
	 }
	 ?>
	 
     </select>
     <br>
	 <br> 
     <input type="submit" name="submitUserData" value="Saada andmed">
   </form>
   <hr>
   
   <?php
     if (isset($_POST["firstName"])){
	   echo "<p>" .$fullName .", olete elanud järgnevatel aastatel: </p> \n";
       echo "<ol> \n";
         for ($i = $_POST["birthYear"]; $i <= date("Y"); $i ++) {
			echo "<li>" .$i ."</li> \n";
		 }
       echo "</ol> \n";	   
  }
   ?>
</body>
</html>