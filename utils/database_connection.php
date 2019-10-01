<?php

/**
 * @file database_connection.php
 *
 * @brief maakt een connectie met de database die we gebruiken.
 *
 * Dit bestand kunnen we simpel inladen als we een verbinding met de database moeten maken.
 */

/// @brief $server De server die we gebruiken.
/// @brief $user De user die we gebruiken.
/// @brief $password Het wachtwoord van de user die we gebruiken.
/// @brief $database De database waarmee we verbinding maken.
/// @brief $mysqli Maakt de daadwerkelijk verbinding met de database,
$server = '';
$user = '';
$password = '';
$database ='';
$mysqli = new mysqli($server, $user, $password, $database);

