<?php
include "menu.php";
require_once "utils/qrcode.php";

if (!isset($_SESSION)) {
    session_start();
}
// Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
if (isset($_SESSION['id']) === false) {
  RedirectToPage("inloggen.php");
}
// Kijk of er een leen_verzoek is gescant
if (isset($_GET['leen_verzoek']) && isset($_GET['token'])) {
  $query = "UPDATE leen_verzoek
            SET token = NULL, status_ = IF(status_ = 'gereserveerd', 'in_gebruik', 'teruggebracht')
            WHERE id = ? AND lener_id = ? AND token = ?";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('iis', $_GET['leen_verzoek'], $_SESSION['id'], $_GET['token']);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    if ($GLOBALS['mysqli']->affected_rows == 0) {
      echo "Er ging iets mis tijdens het ophalen of terugbregen van de fiets, bent u ingelogt op uw account?.";
    }
    else {
      DeleteQR($_GET['leen_verzoek'], $_GET['token']);
      echo "U heeft de fiets met succes opgehaalt of teruggebracht.";
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div class="wrapper">
      <div id="leen_verzoeken">
        <h1 class="titel">Leenverzoeken</h1>
        <table>
          <tr>
            <th> Afbeelding </th>
            <th> Fiets titel </th>
            <th> status </th>
            <th> Bericht </th>
            <th> Prijs per dag </th>
            <th> Ophaalmoment </th>
            <th> Terugbrengmoment </th>
            <th> Lener </th>
          </tr>
          <?php
          $query = "SELECT l.id, l.token, g.id, f.id, f.foto, m.merk_naam, f.model, l.status_, l.bericht, l.prijs, l.ophaal_moment, l.terugbreng_moment, g.naam
                    FROM leen_verzoek l
                    LEFT JOIN fietsen f ON f.id = l.fiets_id
                    LEFT JOIN merk_fiets m ON f.id_merk_fiets = m.id
                    LEFT JOIN gebruiker g ON g.id = l.lener_id
                    WHERE f.gebruiker_id = ? AND (l.status_ = 'in_afwachting' OR l.status_ = 'gereserveerd' OR l.status_ = 'in_gebruik')";
          $stmt = $GLOBALS['mysqli']->prepare($query);
          if (!$stmt) {
            trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
          }
          else {
            $stmt->bind_param('i', $_SESSION['id']);
            if (!$stmt->execute()) {
              echo "ERROR in query";
              return;
            }
            // Echo all de opgehaalde rijen
            $stmt->bind_result($leenVerzoekId, $leenVerzoekToken, $gebruikerId, $fietsId, $foto, $merk_naam, $model, $status, $bericht, $prijs, $ophaal_moment, $terugbreng_moment, $lener);
            $token = "";
            $qrTokens = array();
            while ($stmt->fetch()) {
              if (empty($foto)) { $foto = 'fiets_afbeeldingen/default.png'; }
              else { $foto .= "?t=" .time(); }
              if ($status === "in_afwachting") {
                $status = "<form method='post' action='utils/process_leen_verzoek.php'>
                            <input type='submit' name='geaccepteerd' value='accepteer'>
                            <input type='submit' name='geweigerd' value='weiger'>
                            <input type='number' name='id' value='$leenVerzoekId' style='display: none;'>
                          </form>";
              }
              else if ($status === "gereserveerd" || $status === "in_gebruik") {
                if ($leenVerzoekToken === NULL) { $qrTokens["{$leenVerzoekId}"] = $token;$token = GetToken(); }
                else { $token = $leenVerzoekToken; }
                $status .= ": <a href='qr.php?leen_verzoek=$leenVerzoekId&token=$token'> Klik op deze link en laat de qr code scannen door de lener </a>";
              }
              echo "
              <tr>
                <td> <img src='$foto' style='width:100%; height: auto;'> </td>
                <td> $merk_naam $model </td>
                <td> $status </td>
                <td> $bericht </td>
                <td> € $prijs </td>
                <td> $ophaal_moment </td>
                <td> $terugbreng_moment </td>
                <td> $lener </td>
              </tr>";
            }
            $stmt->close();
            // Genereer de qr's mochten die nog niet bestaan
            foreach ($qrTokens as $key => $value) {
             GenerateQR($key, $value);
            }
          }
          ?>
        </table>
        <h1 class="titel">Geleent</h1>
        <table>
          <tr>
            <th> Afbeelding </th>
            <th> Fiets titel </th>
            <th> status </th>
            <th> Bericht </th>
            <th> Prijs per dag </th>
            <th> Ophaalmoment </th>
            <th> Terugbrengmoment </th>
            <th> Eigenaar </th>
          </tr>
          <?php
          $query = "SELECT f.foto, m.merk_naam, f.model, l.status_, l.bericht, l.prijs, l.ophaal_moment, l.terugbreng_moment, g.naam
                    FROM leen_verzoek l
                    LEFT JOIN fietsen f ON f.id = l.fiets_id
                    LEFT JOIN merk_fiets m ON f.id_merk_fiets = m.id
                    LEFT JOIN gebruiker g ON f.gebruiker_id = g.id
                    WHERE l.lener_id = ? AND (l.status_ = 'in_afwachting' OR l.status_ = 'gereserveerd' OR l.status_ = 'in_gebruik')";
          $stmt = $GLOBALS['mysqli']->prepare($query);
          if (!$stmt) {
            trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
          }
          else {
            $stmt->bind_param('i', $_SESSION['id']);
            if (!$stmt->execute()) {
              echo "ERROR in query";
              return;
            }
            // Echo all de opgehaalde rijen
            $stmt->bind_result($foto, $merk_naam, $model, $status, $bericht, $prijs, $ophaal_moment, $terugbreng_moment, $lener);
            while ($stmt->fetch()) {
              if (empty($foto)) { $foto = 'fiets_afbeeldingen/default.png'; }
              else { $foto .= "?t=" .time(); }
              echo "
              <tr>
                <td> <img src='$foto' style='width:100%; height: auto;'> </td>
                <td> $merk_naam $model </td>
                <td> $status </td>
                <td> $bericht </td>
                <td> € $prijs </td>
                <td> $ophaal_moment </td>
                <td> $terugbreng_moment </td>
                <td> $lener </td>
              </tr>";
            }
            $stmt->close();
          }
          ?>
        </table>
        <!--<h1 class="titel">Geschiedenis</h1>
        <table>
          <tr>
            <th> Afbeelding </th>
            <th> Fiets titel </th>
            <th> status </th>
            <th> Bericht </th>
            <th> Prijs per dag </th>
            <th> Ophaalmoment </th>
            <th> Terugbrengmoment </th>
            <th> Eigenaar </th>
          </tr>
        </table>-->
      </div>
    </div>
  </body>
</html>
