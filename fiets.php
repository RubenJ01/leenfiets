<?php
if (!isset($_SESSION)) {
    session_start();
    require 'utils/database_connection.php';

    if (empty($_GET['succesvol_toegevoegd']) && empty($_GET['fiets_id'])){
        header('location: index.php');
    }

    if(isset($_GET['fiets_id'])){
        //ID meegegeven bijvoorbeeld van overzichtspagina.
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.id, fietsen.gebruiker_id, fietsen.versnellingen, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
        from fietsen, merk_fiets, soort_fiets   
        WHERE fietsen.id_soort_fiets = soort_fiets.id 
        AND fietsen.id_merk_fiets = merk_fiets.id 
        AND fietsen.id = " .$_GET['fiets_id'] ."
        limit 1";
    }
    if(isset($_GET['succesvol_toegevoegd'])){
        //Als een fiets is toegevoegd.  DUS laatste toegevpoegde fiets id gebruiker
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.id, fietsen.gebruiker_id, fietsen.versnellingen, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
        from fietsen, merk_fiets, soort_fiets   
        WHERE fietsen.id_soort_fiets = soort_fiets.id 
        AND fietsen.id_merk_fiets = merk_fiets.id 
        AND fietsen.gebruiker_id = " .$_SESSION['id'] ."
        ORDER BY fietsen.id desc
        limit 1";

    }
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
            }
        }
        else {
            header('location: index.php');
        }
        if(isset($_GET['succesvol_toegevoegd'])){
        echo "<p>Je fiets ".$merk ." ".$model ." is succesvol toegevoegd.</p>";
        }

        ?>
        <img style="width: 600px; float: left" src="<?php
        if (empty($afbeelding)) {
            echo 'fiets_afbeeldingen/default.png';
        }
        else{echo $afbeelding;}?>
        ">
    <table>
        <tr><td>Merk</td><td><?php echo $merk?></td></tr>
        <tr><td>Model</td><td> <?php echo $model?></td></tr>
        <tr><td>Plaats</td><td> <?php echo $plaats?></td></tr>
        <tr><td>Adres</td><td> <?php echo $adres?></td></tr>
        <tr><td>Kleur fiets</td><td> <?php echo $kleur_fiets?></td></tr>
        <tr><td>Geslacht fiets</td><td> <?php echo $geslacht_fiets?></td></tr>
        <tr><td>Versnellingen</td><td> <?php echo $versnellingen?></td></tr>
        <tr><td>Soort fiets</td><td> <?php echo $soort_fiets?></td></tr>
        <tr><td>Borg</td><td> €<?php echo $borg?></td></tr>
        <tr><td>Huurprijs per dag</td><td> €<?php echo $huurprijs_dag?></td></tr>
    </table>
    <?php
    if(!empty($_SESSION['id'])){
    if($fiets_gebruikers_id == $_SESSION['id'] ){
        echo "<a href=\"fiets_bewerken.php?fiets_id=" .$fiets_id ."\">Fiets bewerken</a>";
    }
    else{
        echo "<a href=\"#" .$fiets_id ."\">Fiets huren</a>";
    }}
    else{
        echo "<a href=\"inloggen.php\">Log in</a>";
    }?>
    </body>
</html>

