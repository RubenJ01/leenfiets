<?php
include "menu.php";
include "utils/core_functions.php";
?>
<!DOCTYPE html>
<html>
<head><title>Contact</title></head>
<body>
<form method="GET">
  <?php
  if(!isset($_SESSION["email"])){

echo    '<fieldset>
        <legend>voer email in</legend>
        <label name="email">Email</label>
        <input name="email" type="Email" /><br />
        </fieldset>';

  }
  ?>
    onderwerp
    <br>
    <input type="text" name="onderwerp" value="">
    <br>
    beschrijving
    <br>
    <textarea style="resize: none;"name="bericht" rows="10" cols="50"></textarea>
 <br>
 <input type="submit" name= "Verstuur">
</form>
</body>
</html>
<?php
if(isset($_GET["Verstuur"])){
    $email = NULL;
    if(isset($_SESSION["email"])){
      $email = $_SESSION["email"];

    }
    else{
      $email=$_GET["email"];
      if($email==""){
        echo "vul email in";
        return;
      }
    }
    $onderwerp = $_GET["onderwerp"];
    $bericht=$_GET["bericht"];
    if($onderwerp==""){
      echo "vul een onderwerp in";
      return;
    }
    if($bericht==""){
      echo "vul een bericht in";
      return;
    }
    $bericht = "From: ". $email. "<br>". $bericht;"";
    $ontvanger = "leenfiets2019@gmail.com";
    $error = send_email($ontvanger, $onderwerp, $bericht);
    if ($error == 'false') {
        echo "email is succesvol verzonden";
    } else {
        echo $error;
    }


}
 ?>
