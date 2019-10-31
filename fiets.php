<?php
if (!isset($_SESSION)) {
    session_start();
    require 'utils/database_connection.php';

    if (empty($_GET['succesvol_toegevoegd']) && empty($_GET['fiets_id'])){
        header('location: index.php');
    }

    if(isset($_GET['fiets_id'])){
        //ID meegegeven bijvoorbeeld van overzichtspagina.
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.id, gebruiker.naam, fietsen.omschrijving, fietsen.gebruiker_id, fietsen.versnellingen, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam, fietsen.postcode
        from fietsen, merk_fiets, soort_fiets, gebruiker
        WHERE fietsen.id_soort_fiets = soort_fiets.id
        AND fietsen.id_merk_fiets = merk_fiets.id
        AND fietsen.gebruiker_id = gebruiker.id
        AND fietsen.id = " .$_GET['fiets_id'] ."
        limit 1";
    }
    if(isset($_GET['succesvol_toegevoegd'])){
        //Als een fiets is toegevoegd.  DUS laatste toegevpoegde fiets id gebruiker
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.id, gebruiker.naam, fietsen.omschrijving,fietsen.gebruiker_id, fietsen.versnellingen, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam, fietsen.postcode 
        from fietsen, merk_fiets, soort_fiets, gebruiker
        WHERE fietsen.id_soort_fiets = soort_fiets.id
        AND fietsen.id_merk_fiets = merk_fiets.id
        AND fietsen.gebruiker_id = gebruiker.id
        AND fietsen.gebruiker_id = " .$_SESSION['id'] ."
        ORDER BY fietsen.id desc
        limit 1";

    }
}

?>
<?php
$query = $mysqli->query($sql);
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $GLOBALS['borg'] = $row['borg'];
        $GLOBALS['afbeelding'] = $row['foto'];
        $GLOBALS['merk'] = $row['merk_naam'];
        $GLOBALS['model'] = $row['model'];
        $GLOBALS['plaats'] = $row['plaats'];
        $GLOBALS['adres'] = $row['adres'];
        $GLOBALS['kleur_fiets'] = $row['kleur_fiets'];
        $GLOBALS['geslacht_fiets'] = $row['geslacht_fiets'];
        $GLOBALS['versnellingen'] = $row['versnellingen'];
        $GLOBALS['soort_fiets'] = $row['soort_fiets'];
        $GLOBALS['huurprijs_dag'] = $row['prijs'];
        $GLOBALS['fiets_gebruikers_id'] = $row['gebruiker_id'];
        $GLOBALS['fiets_id'] = $row['id'];
        $GLOBALS['gebruikersnaam'] = $row['naam'];
        $GLOBALS['omschrijving'] = $row['omschrijving'];
        $GLOBALS['postcode'] = $row['postcode'];
    }
}
else {
    header('location: index.php');
}
if(isset($_GET['succesvol_toegevoegd'])){
    echo "<p>Je fiets ".$merk ." ".$model ." is succesvol toegevoegd.</p>";
}

?>
<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>Fiets</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <div><?php include 'menu.php';?></div>
        <div id="fiets">
            <h1><?php echo $merk?> <?php echo $model?></h1>
            <div id="linksKolom">
                <img src="<?php
                if (empty($afbeelding)) {
                    echo 'fiets_afbeeldingen/default.png';
                }
                else{echo $afbeelding ."?t=" .time();}?>
        ">
                <b>Omschrijving</b><div id="omschrijving_scroll"> <?php echo $omschrijving?></div>
                Deze fiets wordt aangeboden door:<a href=<?= '\profiel.php?gebruikers_id=' .$fiets_gebruikers_id?> ><?= $gebruikersnaam?></a>
                <?php
                if(!empty($_SESSION['id'])){
                    if($fiets_gebruikers_id == $_SESSION['id'] ){
                        echo "<a href=\"fiets_bewerken.php?fiets_id=" .$fiets_id ."\">Fiets bewerken</a>";
                    }
                    }
                else{
                    echo "<a href=\"inloggen.php\">Log in</a>";
                }?>
            </div>

<div id="rechtsKolom">

    <table id="specificaties">
        <colgroup>
            <col span="1" style="width: 50%;">
            <col span="1" style="width: 50%;">
        </colgroup>
        <th colspan="2">Specificaties</th>
        <tr><td>Merk</td><td align='right'><?php echo $merk?></td></tr>
        <tr><td>Model</td><td align='right'> <?php echo $model?></td></tr>
        <tr><td>Kleur fiets</td><td align='right'> <?php echo $kleur_fiets?></td></tr>
        <tr><td>Geslacht fiets</td><td align='right'> <?php echo $geslacht_fiets?></td></tr>
        <tr><td>Versnellingen</td><td align='right'> <?php echo $versnellingen?></td></tr>
        <tr><td>Soort fiets</td><td align='right'> <?php echo $soort_fiets?></td></tr>
        <tr><td> </td></tr>
        <th colspan="2">Prijzen</th>
        <tr><td>Huurprijs per dag</td><td align='right'> €<?php echo $huurprijs_dag?></td></tr>
        <tr><td>Borg</td><td align='right'> €<?php echo $borg?></td></tr>
    </table>

    <!-- ___________CALENDAR___________ -->

    <div style="float:right;">
        <?php
        include "utils/database_connection.php";
        include "utils/calendar.php";
        //$date = new DateTime();
        //echo $date->format('Y-m-d H:i:s');

        $query = "SELECT * FROM leen_verzoek";
                ?>
    </div>

    <!-- ______________________________ -->
    <table id="ophaallocatie">

        <th colspan="2">Ophaallocatie</th>
        <tr><td>Plaats</td><td> <?php echo $plaats?></td></tr>
        <tr><td>Adres</td><td> <?php echo $adres?></td></tr>
    </table>
    <div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="300" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $postcode;?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div><style>.mapouter{display: inline-block;position:relative;text-align:right;height:300px;width:100%;float:left}.gmap_canvas {display: inline-block;overflow:hidden;background:none!important;height:300px;width:100%;}</style></div>
</div>



        </div>





    </body>
</html>
