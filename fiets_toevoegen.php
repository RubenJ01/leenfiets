<?php
/**
 * @file fiets_toevoegen.php
 *
 * @brief Gebruikers kunnen hier hun fiets toevoegen.
 *
 * Gebruikers kunnen hier hun fiets toevoegen.
 */
require 'utils/database_connection.php';
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['email'])) {
    header('location: inloggen.php?niet_ingelogd='. urlencode('true'));
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Fiets toevoegen</title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<form method="post" id="fietsentoevoegen" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Merk (*)
    <select name="merk_naam">
        <?php
        $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc())
        {
            ?>
            <option value = <?php echo($row['id'])?> >
                <?php echo($row['merk_naam']) ?>
            </option>
            <?php
        }
        ?>
    </select><br>
    Model(*) <input type="text" name="model" placeholder="Model" required><br>
    Plaats(*) <input type="text" name="plaats" placeholder="Plaats" required><br>
    Adres(*) <input type="text" name="adres" placeholder="Adres" required><br>
    Kleur fiets(*):
    <select name="kleur">
        <option value="Geel">Geel</option>
        <option value="Oranje">Oranje</option>
        <option value="Zwart">Zwart</option>
        <option value="Blauw">Blauw</option>
        <option value="Grijs">Grijs</option>
        <option value="Wit">Wit</option>
        <option value="Wit">Roze</option>
    </select><br>
    Man of vrouw(*)
    <input type="radio" checked="checked" name="geslacht_fiets" value="Man">Mannen fiets
    <input type="radio" name="geslacht_fiets" value="Vrouw">Vrouwen fiets
    <input type="radio" name="geslacht_fiets" value="Onzijdig">Onzijdige fiets<br>
    Versnellingen(*): <input type="number" min="0" max="27" name="versnellingen" placeholder="Aantal versnellingen" required><br>
    Soort fiets(*):
    <select name="soort_fiets">
        <?php
        $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc())
        {
        ?>
        <option value = <?php echo($row['id'])?> >
            <?php echo($row['soort_fiets']) ?>
        </option>
            <?php
        }
        ?>
    </select><br>
    Borg(*): <input type="number" min="0" step="0.01" name="borg" max="1000" placeholder="Borg" required><br>
    Huurprijs per dag(*):<input type="number" step="0.01" min="0" max="200" name="huur-prijs" placeholder="Huurprijs" required><br>
    <input type="file" name="foto" value="foto" id="foto"><br><br>
    <input type="submit" name="toevoegen" value="Toevoegen">
</form>
<?php
if(isset($_POST['toevoegen'])){
    if (!$_FILES["foto"]["name"]){
        echo 'geen foto';
        $sql = "INSERT INTO fietsen(borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen, model) VALUES (".$_POST['borg'].",".$_POST['huur-prijs'].",'".$_SESSION['id']."','".$_POST['plaats']."',".$_POST['soort_fiets'].",".$_POST['merk_naam'].",'".$_POST['adres']."','','".$_POST['geslacht_fiets']."','".$_POST['kleur']."','".$_POST['versnellingen']."','".$_POST['model']."');";
        echo $sql;
        $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
    }
    else{
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
            $sql = "INSERT INTO fietsen(borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen, model) VALUES (".$_POST['borg'].",".$_POST['huur-prijs'].",'".$_SESSION['id']."','".$_POST['plaats']."',".$_POST['soort_fiets'].",".$_POST['merk_naam'].",'".$_POST['adres']."','$target_file','".$_POST['geslacht_fiets']."','".$_POST['kleur']."','".$_POST['versnellingen']."','".$_POST['model']."');";
            echo $sql;
            $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
        } else {
            echo "Uploaden niet gelukt";
        }
    }
    }
}
?>
</body>
</html>