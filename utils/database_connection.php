<?php

/**
 * @file database_connection.php
 *
 * @brief maakt een connectie met de database die we gebruiken.
 *
 * Dit bestand kunnen we simpel inladen als we een verbinding met de database moeten maken.
 */

/// @brief $server De server die we gebruiken.
$server = '104.248.95.21';
/// @brief $user De user die we gebruiken.
$user = 'root';
/// @brief $password Het wachtwoord van de user die we gebruiken.
$password = '';
/// @brief $database De database waarmee we verbinding maken.
$database ='leenfiets';
/// @brief $mysqli Maakt de daadwerkelijk verbinding met de database,
$mysqli = new mysqli($server, $user, $password, $database);

