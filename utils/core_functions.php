<?php
/**
 * @file core_functions.php
 *
 * @brief Functies die meerdere keren door het project heen gebruikt worden.
 *
 * Deze functies kunnen in ek bestand aangeroepen worden.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('plugins/PHPMailer/src/Exception.php');
require_once('plugins/PHPMailer/src/PHPMailer.php');
require_once('plugins/PHPMailer/src/SMTP.php');

/// @brief Deze functie controleert of het wachtwoord wat de gebruiker ingevuld heeft sterk genoeg is.
/// @param $wachtwoord Het wachtwoord wat we willen controleren.
/// @return array
function check_password_strength($wachtwoord){
    $errors = array();
    if (strlen($wachtwoord) < 8) {
        $errors[] = "Je wachtwoord moet minimaal 8 tekens lang zijn.";
    }
    if (!preg_match("#[0-9]+#", $wachtwoord)) {
        $errors[] = "Je wachtwoord moet minstens 1 getal bevatten.";
    }
    if (!preg_match("#[a-zA-Z]+#", $wachtwoord)) {
        $errors[] = "Je wachtwoord moet minstens 1 letter bevatten.";
    }
    return $errors;
}

/// @brief Deze functie verstuurt een email
/// @param $ontvanger Het email waarna de mail verstuurt word.
/// @param $onderwerp Het onderwerp van de mail.
/// @param $body Het bericht, de body.
/// @return $error Deze is of false of een error.
function send_email($ontvanger, $onderwerp, $body){
    $error = 'false';
    $mail = new PHPMailer(true);
    try {
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'leenfiets2019@gmail.com';
        $mail->Password = 'ict_project2019';
        $mail->setFrom('leenfiets2019@gmail.com', 'Mailer');
        $mail->addAddress($ontvanger);
        $mail->isHTML(true);
        $mail->Subject = $onderwerp;
        $mail->Body = $body;
        $mail->send();
    } catch (Exception $e) {
        $error = $mail->ErrorInfo;
    }
    return $error;
}

/// @brief Deze functie geeft een token in hexadecimals. Hou ermee rekening dat de length van de string die je terugkrijgt het dubbele is van de opgegeven length
/// @param $length length is optioneel, de standaard waarde is 8
/// @return string
function GetToken($length = 8) {
  if ($length < 8) {
    $length = 8;
  }
  return bin2hex(random_bytes($length));
}

/// @brief Deze functie is bijna hetzelfde als header() alleen hier heb je de optie om je getters te bewaren als je naar een andere pagina wil redirecten
/// @param $page Met $page kan je aangeven naar welke pagina je wil bv. "index.php"
/// @param $keepGetters $keepGetters is een boolean die standaard false is waarmee je kan aangeven of je de huidige getters wil bewaren tijdens het redirecten naar een andere pagina
/// @return void
function RedirectToPage($page, $keepGetters = false) {
  $header = "Location: $page";
  // Als er getters in de link staan
  if ($keepGetters) {
    $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $pos = strpos($actual_link, '?');
    if ($pos !== false) {
      $header .= substr($actual_link, $pos);
    }
  }
  header($header);
}
