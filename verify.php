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
    $verificatie_code = $_GET['verificatie_code'];
    $select_code = "select verificatiecode, status_code from gebruiker where status_code = 0 and verificatiecode = '$verificatie_code' limit 1";
    $select_query = $mysqli->query($select_code);
    if (!$select_query) {
        die($mysqli->error);
    }
    if ($select_query->num_rows == 1){
        $update_status = "update gebruiker set status_code = 1 where verificatiecode = '$verificatie_code' limit 1";
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