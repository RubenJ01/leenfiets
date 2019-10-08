<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'plugins/PHPMailer/src/Exception.php';
require 'plugins/PHPMailer/src/PHPMailer.php';
require 'plugins/PHPMailer/src/SMTP.php';

require 'utils/database_connection.php';

if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Inloggen</title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<form method="post" id="wachtwoordVergeten" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="email" name="email" placeholder="Je E-mail"><br />
    <input type="password" name="nieuwe_wachtwoord" placeholder="Je nieuwe wachtwoord"><br />
    <input type="password" name="nieuwe_wachtwoord_herhalen" placeholder="Je nieuwe wachtwoord"><br />
    <input type="submit" name="aanvragen" value="Aanvragen">
</form>
</body>
</html>
<?php
if (isset($_POST['aanvragen'])) {
    $nieuwe_wachtwoord = $_POST['nieuwe_wachtwoord'];
    $nieuwe_wachtwoord_herhaald = $_POST['nieuwe_wachtwoord'];
    $email = $_POST['email'];
    $fields = array('nieuwe_wachtwoord', 'nieuwe_wachtwoord_herhalen');
    $error = false;
    foreach ($fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            echo $fieldname . " is niet ingevuld. <br />";
            $error = true;
        }
    }
    $wachtwoord_code = md5(time().$email);
    if ($error ==  false){
        $hashed_wachtwoord = password_hash($nieuwe_wachtwoord, PASSWORD_BCRYPT);
        $sql_code = "update gebruiker set wachtwoordcode = '$wachtwoord_code', nieuwe_wachtwoord = '$hashed_wachtwoord' where email = '$email'";
        $insert_code = $mysqli->query($sql_code);
        if ($insert_code === false){
            die('Invalid query: ' . $mysqli->error);
        }
        if ($mysqli->affected_rows == 1) {
            $mail = new PHPMailer(true);
            try {
                $mail->IsSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = 'leenfiets2019@gmail.com';
                $mail->Password = 'ict_project2019';
                $mail->setFrom('leenfiets2019@gmail.com', 'Leenfiets');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Wachtwoord veranderen';
                $mail->Body = "<a href=localhost/ict-project/nieuw_wachtwoord.php?wachtwoord_code=$wachtwoord_code&email=$email>hier<a/>";
                $mail->send();
                echo "Er is een mail gestuurd om je wachtwoord te resetten.";
            } catch (Exception $e) {
                echo "E-mail niet kunnen verzenden. Mail error {$mail->ErrorInfo}";
            }
        } else {
            echo "Sorry we hebben dat E-mail adres niet kunnen vinden.";
        }
    }
}
?>

