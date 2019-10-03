<?php
/**
 * @file inloggen.php
 *
 * @brief Op deze pagina kunnen gebruikers inloggen.
 *
 * Gebruikers kunnen op deze pagina inloggen. We slaan tijdelijk wat gegevens op in een sessie.
 */

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
<form method="post" id="LoginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="email" name="email" id="email" placeholder="E-Mail adres"> <br />
    <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord"> <br />
    <input type="submit" name="login" value="Inloggen!">
</form>
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
        if ($sql_password->num_rows == 1) {
            while($row = $sql_password->fetch_assoc()) {
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
    }
}
?>