<?php
/**
 * @file verify.php
 *
 * @brief Hier verifieren gebruikers hun account.
 *
 * Dit script verifieert het account van een nieuwe gebruiker.
 */
require 'utils/database_connection.php';

if (isset($_GET['verificatie_code'])){
    /// @brief $verificatie_code Hier halen we de uniek gegenereerde verificatiecode op.
    $verificatie_code = $_GET['verificatie_code'];
    /// @brief $select_code Deze variabele bevat de query de we gebruiken om de verificatie code uit de database te halen.
    $select_code = "select verificatiecode, status_code from gebruiker where status_code = 0 and verificatiecode = '$verificatie_code' limit 1";
    /// @brief $select_query Voert de query select_code uit.
    $select_query = $mysqli->query($select_code);
    if (!$select_query) {
        die($mysqli->error);
    }
    if ($select_query->num_rows == 1){
        /// @brief $update_status Deze query zet de status van de gebruiker op 1 wat betekent dat de gebruiker geverifieerd is.
        $update_status = "update gebruiker set status_code = 1 where verificatiecode = '$verificatie_code' limit 1";
        /// @brief $update_query Voert de query update_query uit.
        $update_query = $mysqli->query($update_status);
        if ($update_query){
            header('location: inloggen.php?verificatie_succesvol='. urlencode('true'));
        } else {
            die($mysqli->error);
        }
    } else {
        echo "Dit account bestaat niet of is al geverifieerd.";
    }
} else {
    die('Sorry er is iets fout gegaan.');
}