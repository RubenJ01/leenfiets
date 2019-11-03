<?php
include "menu.php";
require_once "utils/database_connection.php";

if (!isset($_SESSION)) {
    session_start();
}
// Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
if (isset($_SESSION['id']) === false) {
  RedirectToPage("inloggen.php");
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
          $query = "SELECT l.id, g.id, f.id, f.foto, m.merk_naam, f.model, l.status_, l.bericht, l.prijs, l.ophaal_moment, l.terugbreng_moment, g.naam
                    FROM leen_verzoek l
                    JOIN fietsen f ON f.id = l.fiets_id
                    JOIN merk_fiets m ON f.id_merk_fiets = m.id
                    JOIN gebruiker g ON g.id = l.lener_id
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
            $stmt->bind_result($leenVerzoekId, $gebruikerId, $fietsId, $foto, $merk_naam, $model, $status, $bericht, $prijs, $ophaal_moment, $terugbreng_moment, $lener);
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
                    JOIN fietsen f ON f.id = l.fiets_id
                    JOIN merk_fiets m ON f.id_merk_fiets = m.id
                    JOIN gebruiker g ON f.gebruiker_id = g.id
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
