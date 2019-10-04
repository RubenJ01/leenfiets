<?php
/**
 * @file uitloggen.php
 *
 * @brief Op deze pagina word je uitgelogd.
 *
 * Dit script vernietigd de huidige functie.
 */
if (!isset($_SESSION)) {
    session_start();
}
session_destroy();
header('location: index.php');
?>

