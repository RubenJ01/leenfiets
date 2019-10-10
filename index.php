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
