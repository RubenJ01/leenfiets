<?php
/**
 * @file wachtwoord_vergeten.php
 *
 * @brief gebruikers kunnen op deze pagina een nieuw wachtwoord aanvragen.
 *
 * Vanuit deze pagina word een verzoek gestuurd om het wachtwoord van het opgegeven account te veranderen.
 */

require 'utils/database_connection.php';
require 'utils/core_functions.php';

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
    $errors = check_password_strength($nieuwe_wachtwoord);
    if (count($errors) > 0){
        $error = true;
    }
    foreach($errors as $fault){
        echo $fault . "<br />";
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
           $ontvanger = $email;
           $onderwerp = 'Wachtwoord vergeten.';
           $body = "<a href=http://leenfiets.antonbonder.nl/nieuw_wachtwoord.php?wachtwoord_code=$wachtwoord_code&email=$email>hier<a/>";
           $error = send_email($ontvanger, $onderwerp, $body);
           if ($error == 'false') {
               echo 'Er is een mail verstuurd om je wachtwoord te resetten.';
           } else {
               echo $error;
           }
        }
    }
}
?>

