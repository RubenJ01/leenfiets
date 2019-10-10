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
    <div class="wrapper">
        <h1>Registreren</h1>
        <hr>
        <label for="gebruikersnaam">Gebruikersnaam</label>
        <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Gebruikersnaam"> <br />
        <label for="email">E-mail adres</label>
        <input type="email" name="email" id="email" placeholder="E-Mail adres"> <br />
        <label for name="wachtwoord">Wachtwoord</label>
        <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord"> <br />
        <label for="wachtwoord_twee">Wachtwoord herhalen</label>
        <input type="password" name="wachtwoord_twee" id="wachtwoord_twee" placeholder="Wachtwoord Herhalen"> <br />
        </hr>
        <input type="submit" name="registreer" value="Registreer!" class="registreerknop">
        <p>Heb je al een account? Log <a href="inloggen.php">hier.</a> in.</p>
    </div>
</form>
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
            $ontvanger = $email;
            $onderwerp = "E-mail verificatie";
            $body = "Verifieer je account <a href=localhost/ict-project/verify.php?verificatie_code=$verificatie_code>hier<a/>. ";
            $error = send_email($ontvanger, $onderwerp, $body);
            if ($error == 'false') {
                header('location: inloggen.php?registratie_succesvol=' . urlencode('true'));
            } else {
                echo $error;
            }
        } else {
            echo "Sorry dat e-mail adres is helaas al in gebruik!";
        }
    }
}
?>
