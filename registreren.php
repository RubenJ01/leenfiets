<?php
/**
 * @file registreren.php
 *
 * @brief Op deze pagina worden de registraties van gebruikers verwerkt en verzonden.
 *
 * Gebruikers kunnen op deze pagina een nieuw account registreren en die worden dan opgeslagen in de database.
 */

require 'utils/database_connection.php';
include 'utils/core_functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'plugins/PHPMailer/src/Exception.php';
require 'plugins/PHPMailer/src/PHPMailer.php';
require 'plugins/PHPMailer/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Registreren</title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<form method="post" id="registreerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Gebruikersnaam"> <br />
    <input type="email" name="email" id="email" placeholder="E-Mail adres"> <br />
    <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord"> <br />
    <input type="password" name="wachtwoord_twee" id="wachtwoord_twee" placeholder="Wachtwoord Herhalen"> <br />
    <input type="submit" name="registreer" value="Registreer!">
</form>
<p>Heb je al een account? Log <a href="inloggen.php">hier.</a> in.</p>
</body>
</html>
<?php
if(isset($_POST['registreer'])){
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoord_twee = $_POST['wachtwoord_twee'];
    $error = false;
    if ($wachtwoord != $wachtwoord_twee) {
        $error = true;
        echo 'De opgegeven wachtwoorden zijn niet gelijk <br />';
    }
    $fields = array('gebruikersnaam', 'email', 'wachtwoord', 'wachtwoord_twee');
    foreach ($fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            echo $fieldname . " is niet ingevuld. <br />";
            $error = true;
        }
    }
    $errors = check_password_strength($wachtwoord);
    if (count($errors) > 0){
        $error = true;
    }
    foreach($errors as $fault){
        echo $fault . "<br />";
    }
    $hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_BCRYPT);
    $gebruikersnaam = $mysqli->real_escape_string($gebruikersnaam);
    $email = $mysqli->real_escape_string($email);
    $wachtwoord = $mysqli->real_escape_string($hashed_wachtwoord);
    $verificatie_code = md5(time().$gebruikersnaam);
    $verificatie_code = $mysqli->real_escape_string($verificatie_code);
    if ($error == false) {
        $sql_email = "select * from gebruiker where email = '$email'";
        $email_query = $mysqli->query($sql_email);
        if ($email_query->num_rows == 0) {
            $sql_insert = "insert into gebruiker(naam, email, wachtwoord, verificatiecode) 
                                values(?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql_insert);
            $stmt->bind_param('ssss', $gebruikersnaam, $email, $hashed_wachtwoord, $verificatie_code);
            $stmt->execute();
            if (!$stmt){
                die('Invalid query: ' . $mysqli->error);
            }
            $stmt->close();
            $mail = new PHPMailer(true);
            try {
                $mail->IsSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = 'leenfiets2019@gmail.com';
                $mail->Password = 'ict_project2019';
                $mail->setFrom('leenfiets2019@gmail.com', 'Mailer');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Account verificatie';
                $mail->Body = "Verifieer je account <a href=localhost/ict-project/verify.php?verificatie_code=$verificatie_code>hier<a/>. ";
                $mail->send();
                header('location: inloggen.php?registratie_succesvol='. urlencode('true'));
            } catch (Exception $e) {
                echo "E-mail niet kunnen verzenden. Mail error {$mail->ErrorInfo}";
            }
        } else {
            echo "Sorry dat e-mail adres is helaas al in gebruik!";
        }
    }
}
?>
