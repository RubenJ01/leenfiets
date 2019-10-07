<?php
require 'utils/database_connection.php';
if (!isset($_SESSION)) {
    session_start();
}

$fiets_id = $_GET['fiets_id'];
$gebruiker_id  = $_SESSION['id'];

$sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam from fietsen, merk_fiets, soort_fiets
WHERE fietsen.id_soort_fiets = soort_fiets.id AND fietsen.id_merk_fiets = merk_fiets.id AND fietsen.id = $fiets_id AND fietsen.gebruiker_id = $gebruiker_id limit 1 ";

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
    }
}
else {
    header('location: index.php');
}
?>
<html>
<body>
<div><?php include 'menu.php'; ?></div>
<div class="afbeelding_foto_bewerken">
    <img style="width: 300px;" src="<?php
    if (empty($afbeelding)) {
        echo 'fiets_afbeeldingen/default.png';
    }
    else{ echo $afbeelding  ;}
    ?>"><br>
    <h1>Afbeelding bewerken</h1>
    <br>
<form method="post" id="fietsenbewerken" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>?fiets_id=<?php echo $fiets_id ?>">
    <input type="file" name="foto" value="foto" id="foto"><br>

    <input type="submit" name="foto_bewerken" value="Foto wijzigen">
</form>
</div>

<form method="post" id="fietsenbewerken" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>?fiets_id=<?php echo $fiets_id ?>">
    Merk (*)
    <select name="merk_naam">
        <?php
        $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc())
        {
            ?>
            <option value = <?php echo($row['id'])?> <?php if($merk == ($row['merk_naam'])){echo 'selected';} ?>>
                <?php echo($row['merk_naam']) ?>
            </option>
            <?php
        }
        ?>
    </select><br>
    Model(*) <input type="text" name="model" placeholder="Model" value="<?php echo $model ?>" required><br>
    Plaats(*) <input type="text" name="plaats" placeholder="Plaats" value="<?php echo $plaats ?>" required><br>
    Adres(*) <input type="text" name="adres" placeholder="Adres" value="<?php echo $adres ?>" required><br>
    Kleur fiets(*):
    <select name="kleur" >
        <option value="Geel" <?php if($kleur_fiets == 'Geel'){echo 'selected';} ?>>Geel</option>
        <option value="Oranje" <?php if($kleur_fiets == 'Oranje'){echo 'selected';} ?>>Oranje</option>
        <option value="Zwart" <?php if($kleur_fiets == 'Zwart'){echo 'selected';} ?>>Zwart</option>
        <option value="Blauw" <?php if($kleur_fiets == 'Blauw'){echo 'selected';} ?>>Blauw</option>
        <option value="Grijs" <?php if($kleur_fiets == 'Grijs'){echo 'selected';} ?>>Grijs</option>
        <option value="Wit" <?php if($kleur_fiets == 'Wit'){echo 'selected';} ?>>Wit</option>
        <option value="Roze" <?php if($kleur_fiets == 'Roze'){echo 'selected';} ?>>Roze</option>
    </select><br>
    Man of vrouw(*)
    <input type="radio" <?php if($geslacht_fiets == 'Man'){echo 'checked="checked"';} ?> name="geslacht_fiets" value="Man">Mannen fiets
    <input type="radio" <?php if($geslacht_fiets == 'Vrouw'){echo 'checked="checked"';} ?> name="geslacht_fiets" value="Vrouw" >Vrouwen fiets
    <input type="radio" <?php if($geslacht_fiets == 'Onzijdig'){echo 'checked="checked"';} ?> name="geslacht_fiets" value="Onzijdig" >Onzijdige fiets<br>
    Versnellingen(*): <input type="number" min="0" max="27" name="versnellingen" placeholder="Aantal versnellingen" value="<?php echo $versnellingen ?>" required><br>
    Soort fiets(*):
    <select name="soort_fiets">
        <?php
        $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc())
        {
            ?>
            <option value = <?php echo($row['id'])?> <?php if($soort_fiets == ($row['soort_fiets'])){echo 'selected';} ?> >
                <?php echo($row['soort_fiets']) ?>
            </option>
            <?php
        }
        ?>
    </select><br>
    Borg(*): <input type="number" min="0" step="0.01" name="borg" max="1000" placeholder="Borg" value="<?php echo $borg ?>" required><br>
    Huurprijs per dag(*):<input type="number" step="0.01" min="0" max="200" name="huur-prijs" placeholder="Huurprijs" value="<?php echo $huurprijs_dag ?>" required><br>

    <input type="submit" name="bewerken" value="Bewerken">
    <input type="submit" name="verwijderen" value="Verwijderen">
</form>
<?php
if(isset($_POST['bewerken'])){
    $sql = "UPDATE fietsen SET borg = ".$_POST['borg'].", prijs = ".$_POST['huur-prijs'].", plaats = '".$_POST['plaats']."', id_soort_fiets = ".$_POST['soort_fiets'].", id_merk_fiets = ".$_POST['merk_naam'].", adres = '".$_POST['adres']."', geslacht_fiets = '".$_POST['geslacht_fiets']."', kleur_fiets = '".$_POST['kleur']."', versnellingen = '".$_POST['versnellingen']."', model = '".$_POST['model']."' WHERE id = $fiets_id and gebruiker_id = $gebruiker_id ";
    echo $sql;
    $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
}

if(isset($_POST['foto_bewerken'])){
    if (empty($afbeelding)) {
        $uniekePad = date('dmYHis') .$_SESSION['id'];
        echo $uniekePad;

        $target_dir = "fiets_afbeeldingen/" .$uniekePad;
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            echo "bestand is foto";
            $uploadOk = 1;
        } else {
            echo "Bestand is geen foto";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Niet geupload";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                echo "Is geupload";
                $sql = "UPDATE fietsen SET foto = '$target_file' where id = $fiets_id and gebruiker_id = $gebruiker_id;";
                echo $sql;
                $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
            } else {
                echo "Uploaden niet gelukt";
            }
        }
    }
    else{ $target_dir = "$afbeelding";
        $target_file = $target_dir ;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            echo "bestand is foto";
            $uploadOk = 1;
        } else {
            echo "Bestand is geen foto";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Niet geupload";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                echo "Is geupload";
            } else {
                echo "Uploaden niet gelukt";
            }
        }}

}
?>
<br>
</body>
</html>
