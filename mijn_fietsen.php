<?php
/**
 * @file mijn_fietsen.php
 *
 * @brief Gebruikers kunnen hier hun eigen fietsen bekijken
 *
 * De gebruikers krijgen een overzicht van de fietsen die ze online hebben staan.
 */

if (!isset($_SESSION)) {
    session_start();
}

$id = $_SESSION['id'];

require 'utils/database_connection.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title></title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<div class='profiel_fiets_text'>
    <h2>Op deze pagina bevinden zich alle fietsen die u heeft geupload!<br>U kunt de fietsen verwijderen en aanpassen met de 'Fiets bekijken' knop</h2>
</div>
<?php
$sql = "SELECT f.borg, f.prijs, f.versnellingen, f.id, f.plaats, f.kleur_fiets, f.model, f.geslacht_fiets, f.adres, f.foto, s.soort_fiets, m.merk_naam, f.gebruiker_id 
        FROM fietsen f 
        JOIN merk_fiets m ON f.id_merk_fiets = m.id
        JOIN soort_fiets s ON f.id_soort_fiets = s.id
        WHERE f.gebruiker_id = '$id'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    //maakt blokjes van sql
    while($row = $result->fetch_assoc()) {
        $afbeelding = $row['foto'];
        if (empty($afbeelding)){
            $afbeelding = "fiets_afbeeldingen/default.png";
        }
        echo   "<div class='profiel_fiets'>
                    <h1>$row[merk_naam] $row[model]</h1>
                    <h4>€$row[prijs] per dag<br/></h4>
                    <ul>
                    <li>Plaats: $row[plaats]</li>
                    <li>Soort fiets: $row[soort_fiets]</li>
                    <li>Aantal versnellingen $row[versnellingen]</li>
                    <li>Geslacht fiets: $row[geslacht_fiets]</li>
                    </ul><div class='afbeeldingDiv'>
                    <img src = $afbeelding></div>
                    <a href=fiets.php?fiets_id=$row[id]  >Fiets bekijken</a>
                    </div>";
    }
}
else { echo "Je hebt nog geen fietsen geposted"; }
?>
</body>
</html>
