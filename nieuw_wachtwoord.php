<?php
/**
 * @file nieuw_wachtwoord.php
 *
 * @brief Hier word het wachtwoord van een gebruiker verandert.
 *
 * Dit script verandert het wachtwoord van een gebruiker, via een link uit een email.
 */
require 'utils/database_connection.php';

if (isset($_GET['wachtwoord_code'])){
    /// @brief $wachtwoord_code Hier halemn we de uniek gegeneerdere wachtwoord code uit de url op.
    $email = $_GET['email'];
    $wachtwoord_code = $_GET['wachtwoord_code'];
    $select_code = "select wachtwoordcode from gebruiker where email = '$email' limit 1";
    $code_query = $mysqli->query($select_code);
    $select_wachtwoord = "select nieuwe_wachtwoord from gebruiker where wachtwoordcode = '$wachtwoord_code' limit 1";
    $wachtwoord_query = $mysqli->query($select_wachtwoord);
    if ($wachtwoord_query->num_rows == 1) {
        while ($row = $wachtwoord_query->fetch_assoc()){
            $nieuw_wachtwoord = $row["nieuwe_wachtwoord"];
        }
        if ($code_query->num_rows == 1) {
            $update_password = "update gebruiker set wachtwoord = '$nieuw_wachtwoord' where wachtwoordcode = '$wachtwoord_code' limit 1";
            $sql_update = $mysqli->query($update_password);
            echo "succes";
        }
    }
} else {
    echo "Sorry er is iets mis gegaan";
}

