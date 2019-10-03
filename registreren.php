<?php
/**
 * @file registreren.php
 *
 * @brief Op deze pagina worden de registraties van gebruikers verwerkt en verzonden.
 *
 * Gebruikers kunnen op deze pagina een nieuw account registreren en die worden dan opgeslagen in de database.
 */

require 'utils/database_connection.php';
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
        <input type="submit" name="registreer" value="Registreer!">
    </form>
    </body>
    </html>
<?php
if(isset($_POST['registreer'])){
    $gebruikersnaam = $_POST['gebruikersnaam']; ///< @brief $gebruikersnaam De gebruikersnaam.
    $email = $_POST['email']; ///< @brief $email Het e-mail adres.
    $wachtwoord = $_POST['wachtwoord']; ///< @brief $wachtwoord Het wachtwoord.
    $error = false; ///< @brief $error word op false gezet.
    $fields = array('gebruikersnaam', 'email', 'wachtwoord'); ///< @brief $fields Een array met alle fields.
    foreach ($fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            echo $fieldname . " is niet ingevuld. <br />";
            $error = true;
        }
    }
    if ($error == false) {
        $sql_email = "select * from gebruikers where email = '$email'"; ///< @brief $sql_email Query om te kijken of het opgegeven email adres al bestaat.
        $email_query = $mysqli->query($sql_email); ///< @brief $email_query Voert de $sql_email query uit.
        if ($email_query->num_rows == 0) {
            $sql_insert = "insert into gebruikers(naam, email, wachtwoord) 
                                values('$gebruikersnaam', '$email', '$wachtwoord')"; ///< @brief $sql_insert Een query om de nieuwe user in de database te zetten.
            $insert_query = $mysqli->query($sql_insert); ///< @brief $insert_query Voert de $sql_insert query uit.
            echo 'Gebruiker succesvol toegevoegd.';
        } else {
            echo "Sorry dat e-mail adres is helaas al in gebruik!";
        }
    }
}
?>
