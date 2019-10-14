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
require 'utils/database_connection.php';
require 'fietsen/qrcode.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
</head>
<body>
    <div><?php include 'menu.php'; ?></div>
    <?php
    //GenerateQR(1);
    // Check of de gebruiker de qr code heeft gescant
    if (isset($_GET['qr']) && isset($_GET['fietsId']) && isset($_GET['token'])) {
      echo "test";
      $fietsId = $_GET['fietsId'];
      $token = $_GET['token'];
      // Voor query uit om de status en de token van de juiste fiets op te halen
      $query = "SELECT status, token
                FROM fietsen
                WHERE id=?";
      $stmt = $mysqli->prepare($query);
      if (!$stmt) {
        trigger_error($mysqli->error, E_USER_ERROR);
      }
      else {
        $stmt->bind_param('i', $fietsId);
        if (!$stmt->execute()) {
          trigger_error($stmt->error, E_USER_ERROR);
        }
        $status = null;
        $tokenDB = null;
        $stmt->bind_result($status, $tokenDB);
        $stmt->fetch();
        $stmt->close();
        echo "{$tokenDB} en $token";
        // Kijk of de token matcht met de token in de database
        if ($tokenDB !== null && $token === $tokenDB) {
          // Delete de qr code naar dat die succesvol is gescant
          DeleteQR($fietsId);
          // Verander de status in de database
          if ($status == "beschikbaar") {
            $status = "uitgeleend";
          }
          else {
            $status = "beschikbaar";
          }
          $query = "UPDATE fietsen
                    SET status = ?, token = NULL
                    WHERE id = ?";
          $stmt2 = $mysqli->prepare($query);
          if (!$stmt2) {
            trigger_error($mysqli->error, E_USER_ERROR);
          }
          else {
            $copyFietsId = $fietsId;
            $stmt2->bind_param('si', $status, $copyFietsId);
            if (!$stmt2->execute()) {
              trigger_error($stmt2->error, E_USER_ERROR);
            }
            // Geef een bericht aan de gebruiker over de status van de fiets
            if ($status == "beschikbaar") { echo "Je hebt de fiets met succes teruggebracht"; } else { echo "Je hebt de fiets met succes opgehaalt!"; }
            $stmt2->close();
          }
        }
      }
    }
    $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.id, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam
        from fietsen, merk_fiets, soort_fiets
        WHERE fietsen.id_soort_fiets = soort_fiets.id
        AND fietsen.id_merk_fiets = merk_fiets.id ";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        //maakt blokjes van sql
        while($row = $result->fetch_assoc()) {
            $afbeelding = $row['foto'];
            if (empty($afbeelding)){
                $afbeelding = "fiets_afbeeldingen/default.png";

            }
            echo   "<div class= 'fiets_blok'>
                    <h1>$row[merk_naam] $row[model]</h1>
                    <h4>€$row[prijs] per dag</h4>
                    <ul>
                    <li>Plaats: $row[plaats]</li>
                    <li>Soort fiets: $row[soort_fiets]</li>
                    <li>Aantal versnellingen $row[versnellingen]</li>
                    <li>Geslacht fiets:: $row[geslacht_fiets]</li>
                    </ul>
                    <img src = $afbeelding>
                    <a href=fiets.php?fiets_id=$row[id]  >Fiets bekijken</a>
                    </div>";
        }
    } else { echo "Er zijn momenteel geen fietsen beschikbaar"; }






    ?>
</body>
</html>
