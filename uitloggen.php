<?php
/**
 * @file index.php
 *
 * @brief Dit is de homepagina, hier worden de fietsen getoond.
 *
 * Gebruikers kunnen zoeken naar fietsen.
 */
if (!isset($_SESSION)) {
    session_start();
}
session_destroy();
header('location: index.php');
?>

