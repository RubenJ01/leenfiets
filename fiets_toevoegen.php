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
<?php
if(isset($_POST['toevoegen'])){
    $omschrijving = str_replace("\n","<br>",$_POST['omschrijving']);
    if (!$_FILES["foto"]["name"]){
        //geen foto
        $sql = "INSERT INTO fietsen
                    (borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen, model, omschrijving) 
                    VALUES (" . $_POST['borg'] . "," . $_POST['huur-prijs'] . ",'" . $_SESSION['id'] . "','" . $_POST['plaats'] . "'," . $_POST['soort_fiets'] . "," . $_POST['merk_naam'] . ",'" . $_POST['adres'] . "','','" . $_POST['geslacht_fiets'] . "','" . $_POST['kleur'] . "','" . $_POST['versnellingen'] . "','" . $_POST['model'] . "','".$omschrijving."');";
        $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
        header('location: fiets.php?succesvol_toegevoegd='. urlencode('true'));
    }
    else{
        $uniekePad = date('dmYHis') .$_SESSION['id'];
        $target_dir = "fiets_afbeeldingen/" .$uniekePad;
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $uploadOk = 1;
        $fotoAfmetingen = getimagesize($_FILES["foto"]["tmp_name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            //bestand is een foto
            $uploadOk = 1;
        }
        else {
            echo "Bestand is geen foto";
            $uploadOk = 0;
        }
        if($fotoAfmetingen[0] != $fotoAfmetingen[1]) {
            //Foto is geen vierkant
            echo 'Upload een vierkante foto';
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Afbeelding niet geupload, probeer het opnieuw.";
        }
        else {// if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                //echo Is geupload
                $sql = "INSERT INTO fietsen
                            (borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen, model, omschrijving) 
                            VALUES (".$_POST['borg'].",".$_POST['huur-prijs'].",'".$_SESSION['id']."','".$_POST['plaats']."',".$_POST['soort_fiets'].",".$_POST['merk_naam'].",'".$_POST['adres']."','$target_file','".$_POST['geslacht_fiets']."','".$_POST['kleur']."','".$_POST['versnellingen']."','".$_POST['model']."','".$_POST['omschrijving']."');";
                               $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
                header('location: fiets.php?succesvol_toegevoegd='. urlencode('true'));
            }
            else {
                echo "Afbeelding niet geupload, probeer het opnieuw.";
            }
        }
    }
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
            <table>
                <tr><td>Merk (*)</td><td><select name="merk_naam"><?php
                            $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
                            $result = $mysqli->query($sql);
                            while($row = $result->fetch_assoc())
                            {
                                ?>
                                <option value =<?php echo($row['id'])?>><?php echo($row['merk_naam']) ?></option><?php
                            }
                            ?>
                        </select><br></td></tr>
                <tr><td>Model(*)</td><td><input type="text" name="model" placeholder="Model" required></td></tr>
                <tr><td>Plaats(*)</td><td><input type="text" name="plaats" placeholder="Plaats" required></td></tr>
                <tr><td>Adres(*)</td><td><input type="text" name="adres" placeholder="Adres" required></td></tr>
                <tr><td>Kleur fiets(*):</td><td><select name="kleur">
                            <option value="Geel">Geel</option>
                            <option value="Oranje">Oranje</option>
                            <option value="Zwart">Zwart</option>
                            <option value="Blauw">Blauw</option>
                            <option value="Grijs">Grijs</option>
                            <option value="Wit">Wit</option>
                            <option value="Wit">Roze</option>
                        </select></td></tr>
                <tr><td>Geslacht fiets(*)</td><td><input type="radio" checked="checked" name="geslacht_fiets" value="Man">Mannen fiets
                        <input type="radio" name="geslacht_fiets" value="Vrouw">Vrouwen fiets
                        <input type="radio" name="geslacht_fiets" value="Onzijdig">Onzijdige fiets</tr>
                <tr><td>Versnellingen(*)</td><td><input type="number" min="0" max="27" name="versnellingen" placeholder="Aantal versnellingen" required></td></tr>
                <tr><td>Soort fiets(*)</td><td><select name="soort_fiets">
                            <?php
                            $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
                            $result = $mysqli->query($sql);
                            while($row = $result->fetch_assoc()) {
                                ?>
                                <option value = <?php echo($row['id'])?>><?php echo($row['soort_fiets'])?></option><?php
                            }
                            ?>
                        </select></td></tr>
                <tr><td>Borg(*)</td><td><input type="number" min="0" step="0.01" name="borg" max="1000" placeholder="Borg" required></td></tr>
                <tr><td>Huurprijs per dag(*)</td><td><input type="number" step="0.01" min="0" max="200" name="huur-prijs" placeholder="Huurprijs" required></td></tr>
                <tr><td>Omschrijving</td><td><textarea style="resize: none;"name="omschrijving" rows="5" cols="50"></textarea></td></tr>
                <tr><td>Afbeelding:</td><td><input type="file" name="foto" value="foto" id="foto"></td></tr>
                <tr><td><input type="submit" name="toevoegen" value="Toevoegen"></td></tr>
            </table>
        </form>

    </body>
</html>