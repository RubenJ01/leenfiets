<?php

/**
 * @file test_database_connection.php
 *
 * @brief Een script dat controleert of de connectie met de database succesvol is.
 *
 * Script dat je in de console runt om te kijken of de connectie met de database succesvol is.
 *
 */

require "database_connection.php";

/// @brief Dit is een functie die checkt of de verbinding met de database succesvol is.
/// @param $mysqli de verbinding met de database.
/// @return Void
function checkConnection($mysqli)
{
    if (!$mysqli) {
        die("Verbinding gefaald: " . mysqli_connect_error());
    }
    echo "Verbinding succesvol!";
}

checkConnection($mysqli);

