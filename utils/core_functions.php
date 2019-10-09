<?php
/**
 * @file core_functions.php
 *
 * @brief Functies die meerdere keren door het project heen gebruikt worden.
 *
 * Deze functies kunnen in ek bestand aangeroepen worden.
 */

/// @brief Deze functie controleert of het wachtwoord wat de gebruiker ingevuld heeft sterk genoeg is.
/// @param $wachtwoord Het wachtwoord wat we willen controleren.
/// @return array
function check_password_strength($wachtwoord){
    $errors = array();
    if (strlen($wachtwoord) < 8) {
        $errors[] = "Je wachtwoord moet minimaal 8 tekens lang zijn.";
    }
    if (!preg_match("#[0-9]+#", $wachtwoord)) {
        $errors[] = "Je wachtwoord moet minstens 1 getal bevatten.";
    }
    if (!preg_match("#[a-zA-Z]+#", $wachtwoord)) {
        $errors[] = "Je wachtwoord moet minstens 1 letter bevatten.";
    }
    return $errors;
}