<?php

/**
 * @file core_functions.php
 *
 * @brief Lijst met functies voor het geld van een gebruiker.
 *
 * Dit bestand maakt gebruiker van database_connection.php
 */

// Voeg de database_connection.php toe. Als we process geld includen in een van de root bestanden dan moeten we in de utils folder kijken en anders niet
$db_conn_file = "database_connection.php";
if (file_exists("database_connection.php") == false) {
  $db_conn_file = ("utils/".$db_conn_file);
}
require_once $db_conn_file;

/// @brief Deze functie voegt geld toe aan een gebruiker
/// @param $id Het id van de gebruiker waar we het geld van willen toevoegen
/// @param $amount De hoeveelheid geld dat we willen toevoegen
/// @return void
function AddMoney($id, $amount) {
  $query = "UPDATE gebruiker
            SET geld=(geld+?)
            WHERE id = ?";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('di', $amount, $id);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    $stmt->close();
  }
}

/// @brief Deze functie haalt geld op voor de gebruiker als dat mogelijk is en anders word alleen het maximaal mogelijke opgehaalt
/// @param $id Het id van de gebruiker waar we het geld van willen ophalen
/// @param $amount De hoeveelheid geld dat we willen ophalen
/// @return void
function CollectMoney($id, $amount) {
  $query = "UPDATE gebruiker
            SET geld=(
              SELECT IF((geld-k.kosten)>=?, (geld-?), IF((geld-k.kosten)>=0, (geld-(geld-k.kosten)), geld))
              FROM (
                  SELECT (SUM((DATEDIFF(terugbreng_moment, ophaal_moment)+1)*prijs) + (
                  SELECT sum(b.borg)
                  FROM (
                      SELECT borg
                      FROM leen_verzoek
                      WHERE lener_id = ?
                      GROUP BY fiets_id, lener_id, borg
                  ) AS b
                )) as kosten
                FROM leen_verzoek
                WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')
              ) as k
            )
            WHERE id = ?";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('ddiii', $amount, $amount, $id, $id, $id);
    //$stmt->bind_value([':amount' => $amount, ':id' => $id]);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    $stmt->close();
  }
}

/// @brief Deze functie haalt al het geld op dat de gebruiker bij ons heeft staan
/// @param $id Het id van de gebruiker waar we het geld van willen ophalen
/// @return false als er iets mis gaat tijdens ophalen van geld anders return geld
function GetMoney($id) {
  $query = "SELECT geld
            FROM gebruiker
            WHERE id = ?";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    // Echo all de opgehaalde rijen
    $stmt->bind_result($geld);
    $stmt->fetch();
    $stmt->close();
    return $geld;
  }
  return false;
}

/// @brief Deze functie haalt de totale borg op
/// @param $id Het id van de gebruiker waar we het geld van willen ophalen
/// @return return false als er iets mis gaat tijdens ophalen van borg anders return de borgkosten
function GetDepositCosts($id) {
  $query = "SELECT COALESCE(sum(l.borg), 0)
            FROM (
                SELECT borg
                FROM leen_verzoek
                WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')
                GROUP BY fiets_id, lener_id, borg
            ) AS l";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    // Echo all de opgehaalde rijen
    $stmt->bind_result($borgKosten);
    $stmt->fetch();
    $stmt->close();
    return $borgKosten;
  }
  return false;
}

/// @brief Deze functie haalt al het geld op dat gebruikt moet worden voor het lenen van fietsen
/// @param $id Het id van de gebruiker waar we het geld van willen ophalen
/// @return return false als er iets mis gaat tijdens ophalen van leenkosten anders return de leenkosten
function GetBorrowingCosts($id) {
  $query = "SELECT COALESCE(SUM((DATEDIFF(terugbreng_moment, ophaal_moment)+1)*prijs), 0)
            FROM leen_verzoek
            WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    // Echo all de opgehaalde rijen
    $stmt->bind_result($geld);
    $stmt->fetch();
    $stmt->close();
    return $geld;
  }
  return false;
}

/// @brief Deze functie haalt al het geld op dat de gebruiker kan besteden of ophalen. Dus zijn geld - alle leenkosten en borg
/// @param $id Het id van de gebruiker waar we het besteedbaar geld willen van weten
/// @return void
function GetSpendableMoney($id) {
  $query = "SELECT IF((g.geld-k.kosten)>0, g.geld-k.kosten, 0)
            FROM gebruiker g JOIN (
              SELECT IFNULL((SELECT COALESCE(SUM((DATEDIFF(terugbreng_moment, ophaal_moment)+1)*prijs), 0) + (
                SELECT COALESCE(sum(b.borg), 0)
                FROM (
                    SELECT borg
                    FROM leen_verzoek
                    WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')
                    GROUP BY fiets_id, lener_id, borg
                ) AS b
              )), 0) as kosten
              FROM leen_verzoek
              WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')
            ) AS k
            WHERE g.id = ?
            ";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('iii', $id, $id, $id);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    // Echo all de opgehaalde rijen
    $stmt->bind_result($geld);
    $stmt->fetch();
    $stmt->close();
    return $geld;
  }
  return false;
}




// Als er op geld toevoegen is geklikt voeg geld toe aan gebruiker
if(isset($_POST['toevoegen'])) {
  // Start de sessie mocht dat nog niet gedaan zijn
  if (isset($_SESSION) === false) {
      session_start();
  }
  // Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
  if (isset($_SESSION['id']) === false) {
    RedirectToPage("inloggen.php");
  }
  $geld =  $GLOBALS['mysqli']->real_escape_string((float)$_POST['geld']);
  AddMoney($_SESSION['id'], abs($geld));
  header("Location: ../geld.php");
}
// Als er op geld ophalen is geklikt haal dan geld op voor de gebruiker als dat mogelijk is
if(isset($_POST['ophalen'])) {
  // Start de sessie mocht dat nog niet gedaan zijn
  if (isset($_SESSION) === false) {
      session_start();
  }
  // Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
  if (isset($_SESSION['id']) === false) {
    RedirectToPage("inloggen.php");
  }
  $geld =  ((float)$_POST['geld']);
  CollectMoney($_SESSION['id'], abs($geld));
  header("Location: ../geld.php");
}

?>
