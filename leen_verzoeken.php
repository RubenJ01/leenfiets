<?php
include "menu.php";
require_once "utils/qrcode.php";
require_once "utils/process_leen_verzoek.php";

if (!isset($_SESSION)) {
    session_start();
}
// Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
if (isset($_SESSION['id']) === false) {
  header("Location: inloggen.php");
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

        <?php
          // Het kan zijn dat een fiets verlopen is dus daarom roepen we de functie Updatebicycles aan die checkt of er ook iets verlopen is
          UpdateBicycles($_SESSION['id']);

          //Check of er een bericht in de getter staat
          if (isset($_GET['message'])) {
            echo $_GET['message'];
          }
          // Kijk of er een leen_verzoek is gescant
          if (isset($_GET['leen_verzoek']) && isset($_GET['token'])) {
            echo CollectReturnBike($_SESSION['id'], $_GET['leen_verzoek'], $_GET['token']);
          }
        ?>


        <!-- GEKREGEN LEENVERZOEKEN -->


        <h1 class="titel">Gekregen leenverzoeken</h1>
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
                            <input type='submit' name='geaccepteerd' value='accepteer' class='wordBreakDownButton'>
                            <input type='submit' name='geannuleerd' value='annuleer' class='wordBreakDownButton'>
                            <input type='number' name='id' value='$leenVerzoekId' style='display: none;'>
                          </form>";
              }
              else if ($status === "gereserveerd" || $status === "in_gebruik") {
                if ($leenVerzoekToken === NULL) { $token = GetToken();$qrTokens["{$leenVerzoekId}"] = $token; }
                else { $token = $leenVerzoekToken; }
                $g = false;
                if ($status === "gereserveerd") { $g = true; }
                $status .= ": <a href='qr.php?leen_verzoek=$leenVerzoekId&token=$token'> Klik op deze link en laat de qr code scannen door de lener </a>";
                if ($g === true) {
                  $status .= "<form method='post' action='utils/process_leen_verzoek.php'>
                                <input type='submit' name='geannuleerd' value='of klik hier om te annuleren' class='wordBreakDownButton'>
                                <input type='number' name='id' value='$leenVerzoekId' style='display: none;'>
                              </form>";
                }
              }
              echo "
              <tr>
                <td> <img src='$foto' style='width:100%; height: auto;'> </td>
                <td> <a href='fiets.php?fiets_id=$fietsId'> $merk_naam $model </a> </td>
                <td> $status </td>
                <td> $bericht </td>
                <td> € $prijs </td>
                <td> $ophaal_moment </td>
                <td> $terugbreng_moment </td>
                <td> <a href='profiel.php?gebruikers_id=$gebruikerId'> $lener </a></td>
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


        <!-- GESCHIEDENIS UITGELEENDE FIETSEN -->


        <h3 class="titel">Geschiedenis uitgeleende fietsen</h3>
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
                  WHERE f.gebruiker_id = ? AND (l.status_ = 'geannuleerd' OR l.status_ = 'verlopen' OR l.status_ = 'teruggebracht')";
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
            echo "
            <tr>
              <td> <img src='$foto' style='width:100%; height: auto;'> </td>
              <td> <a href='fiets.php?fiets_id=$fietsId'> $merk_naam $model </a> </td>
              <td> $status </td>
              <td> $bericht </td>
              <td> € $prijs </td>
              <td> $ophaal_moment </td>
              <td> $terugbreng_moment </td>
              <td> <a href='profiel.php?gebruikers_id=$gebruikerId'> $lener </a></td>
            </tr>";
          }
          $stmt->close();
        }
        ?>
        </table>


        <!-- FIETSEN DIE U WILT LENEN OF LEENT -->


        <h1 class="titel">Fietsen die u wilt lenen of leent</h1>
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
          $query = "SELECT l.id, f.foto, m.merk_naam, f.model, l.status_, l.bericht, l.prijs, l.ophaal_moment, l.terugbreng_moment, g.naam, g.id
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
            $stmt->bind_result($leenVerzoekId, $foto, $merk_naam, $model, $status, $bericht, $prijs, $ophaal_moment, $terugbreng_moment, $eigenaar, $eigenaarId);
            while ($stmt->fetch()) {
              if (empty($foto)) { $foto = 'fiets_afbeeldingen/default.png'; }
              else { $foto .= "?t=" .time(); }
              $codeForm = "";
              if ($status === "gereserveerd" || $status === "in_gebruik") {
                $codeForm = ".<form method='post' action='utils/process_leen_verzoek.php'>
                                Voer hier je code in die je hebt gekregen van de eigenaar of scan zijn qr: <input type='text' name='code' class='wordBreakDownButton' style='width:90%'>
                                <input type='submit' name='token' value='submit' class='wordBreakDownButton'>
                                <input type='number' name='id' value='$leenVerzoekId' style='display: none;'>
                              </form>";
              }
              if ($status === "in_afwachting" || $status === "gereserveerd") {
                $status .= $codeForm;
                $status .= "<form method='post' action='utils/process_leen_verzoek.php'>
                            <input type='submit' name='geannuleerd' value='of klik hier om te annuleren' class='wordBreakDownButton'>
                            <input type='number' name='id' value='$leenVerzoekId' style='display: none;'>
                          </form>";
              }
              else {
                $status .= $codeForm;
              }
              echo "
              <tr>
                <td> <img src='$foto' style='width:100%; height: auto;'> </td>
                <td> <a href='fiets.php?fiets_id=$fietsId'> $merk_naam $model </a> </td>
                <td> $status </td>
                <td> $bericht </td>
                <td> € $prijs </td>
                <td> $ophaal_moment </td>
                <td> $terugbreng_moment </td>
                <td> <a href='profiel.php?gebruikers_id=$eigenaarId'> $eigenaar </td>
              </tr>";
            }
            $stmt->close();
          }
          ?>
        </table>


        <!-- GESCHIEDENIS GELEENDE FIETSEN -->


        <h3 class="titel">Geschiedenis geleende fietsen</h3>
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
          $query = "SELECT f.foto, m.merk_naam, f.model, l.status_, l.bericht, l.prijs, l.ophaal_moment, l.terugbreng_moment, g.naam, g.id
                    FROM leen_verzoek l
                    LEFT JOIN fietsen f ON f.id = l.fiets_id
                    LEFT JOIN merk_fiets m ON f.id_merk_fiets = m.id
                    LEFT JOIN gebruiker g ON f.gebruiker_id = g.id
                    WHERE l.lener_id = ? AND (l.status_ = 'geannuleerd' OR l.status_ = 'verlopen' OR l.status_ = 'teruggebracht')";
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
            $stmt->bind_result($foto, $merk_naam, $model, $status, $bericht, $prijs, $ophaal_moment, $terugbreng_moment, $eigenaar, $eigenaarId);
            while ($stmt->fetch()) {
              if (empty($foto)) { $foto = 'fiets_afbeeldingen/default.png'; }
              else { $foto .= "?t=" .time(); }
              echo "
              <tr>
                <td> <img src='$foto' style='width:100%; height: auto;'> </td>
                <td> <a href='fiets.php?fiets_id=$fietsId'> $merk_naam $model </a> </td>
                <td> $status </td>
                <td> $bericht </td>
                <td> € $prijs </td>
                <td> $ophaal_moment </td>
                <td> $terugbreng_moment </td>
                <td> <a href='profiel.php?gebruikers_id=$eigenaarId'> $eigenaar </td>
              </tr>";
            }
            $stmt->close();
          }
          ?>
        </table>
      </div>
    </div>
  </body>
</html>
