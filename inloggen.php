<?php
/**
 * @file inloggen.php
 *
 * @brief Op deze pagina kunnen gebruikers inloggen.
 *
 * Gebruikers kunnen op deze pagina inloggen. We slaan tijdelijk wat gegevens op in een sessie.
 */

require 'utils/database_connection.php';
require 'utils/core_functions.php';

if (!isset($_SESSION)) {
    session_start();
}
// Als de gebruiker al is ingelogt redirect de gebruiker dan naar de hoofdpagina
if (isset($_SESSION['email'])) {
  RedirectToPage("index.php", true); // Dit is geen standaar functie maar een functie die in core_functions.php staat
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
<?php
if(isset($_GET['registratie_succesvol'])){
    echo "<p>Check je mailbox om je email te verifieren.</p>";
}

if(isset($_GET['niet_ingelogd'])){
    echo "<p>Eerst inloggen om een fiets te plaatsen.</p>";
}

if(isset($_GET['verificatie_succesvol'])){
    echo "<p>Je account is met succes geverifieerd. Je kunt nu inloggen.</p>";
}
if(isset($_GET['wachtwoord_reset'])){
    echo "<p>Je wachtwoord is met succes gerest. je kunt nu inloggen.</p>";
}
?>
<form method="post" id="LoginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="email" name="email" id="email" placeholder="E-Mail adres"> <br />
    <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord"> <br />
    <input type="submit" name="login" value="Inloggen!">
</form>
<p>Nog niet geregistreerd? Registreer je <a href="registreren.php">hier.</a></p>
<p>Wachtwoord vergeten? Vraag een nieuwe <a href="wachtwoord_vergeten.php">aan.</a></p>
</body>
</html>
<?php
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];
    $fields = array('email', 'wachtwoord'); ///< @brief $fields Een array met alle fields.
    $error = false;
    foreach ($fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            echo $fieldname . " is niet ingevuld. <br />";
            $error = true;
        }
    }
    if(!$error){
        $sql_get_password = "select wachtwoord from gebruiker where email = '$email'";
        $sql_password = $mysqli->query($sql_get_password);
        $check_status = "select status_code from gebruiker where email = '$email' and status_code = 1";
        $check_query = $mysqli->query($check_status);
        $sql_get_id = "select id from gebruiker where email = '$email'";
        $sql_id_query = $mysqli->query($sql_get_id);
        while ($row = $sql_id_query->fetch_assoc()) {
            $_SESSION['id'] = $row['id'];
        }
        if ($check_query->num_rows == 1) {
            if ($sql_password->num_rows == 1) {
                while ($row = $sql_password->fetch_assoc()) {
                    if (password_verify($wachtwoord, $row['wachtwoord'])) {
                        $_SESSION['email'] = $email;
                        header('location: index.php');
                    } else {
                        echo 'Wachtwoord incorrect.';
                    }
                }
            } else {
                echo 'Er bestaat geen gebruiker met dat E-mail adres.';
            }
        } else {
            echo "Je account is nog niet geverifieerd. Check je mail voor instructies.";
        }
    }
}
?>
