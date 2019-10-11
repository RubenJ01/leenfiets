<!DOCTYPE html>
<?php
include "menu.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'plugins/PHPMailer/src/Exception.php';
require 'plugins/PHPMailer/src/PHPMailer.php';
require 'plugins/PHPMailer/src/SMTP.php';
 ?>
<html>
<head><title>Contact</title></head>
<body>
<form method="post">
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
if(isset($_POST["Verstuur"])){
    $email = NULL;
    if(isset($_SESSION["email"])){
      $email = $_SESSION["email"];

    }
    else{
      $email=$_POST["email"];
      if($email==""){
        echo "vul email in";
        return;
      }
    }
    $onderwerp = $_POST["onderwerp"];
    $bericht=$_POST["bericht"];
    if($onderwerp==""){
      echo "vul een onderwerp in";
      return;
    }
    if($bericht==""){
      echo "vul een bericht in";
      return;
    }
    $bericht = "From: ". $email. "<br>". $bericht;
    $mail = new PHPMailer(true);
    try {
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'leenfiets2019@gmail.com';
        $mail->Password = 'ict_project2019';
        $mail->setFrom('leenfiets2019@gmail.com', 'Mailer');
        $mail->addAddress("leenfiets2019@gmail.com");
        $mail->isHTML(true);
        $mail->Subject = $onderwerp;
        $mail->Body = $bericht;
        $mail->send();
    } catch (Exception $e) {
        echo "E-mail niet kunnen verzenden. Mail error {$mail->ErrorInfo}";
    }
    echo "email is succesvol verzonden";

}




 ?>
