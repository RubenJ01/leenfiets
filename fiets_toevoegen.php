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
    $error = array();
    //Alle post data controleren en eventueel eenpassen
    $omschrijving = $GLOBALS['mysqli']->real_escape_string(str_replace("\n","<br>",$_POST['omschrijving']));

    //Checken of model niet langer is dan 20 teken
    if (strlen($_POST['model']) > 20){
        //Model te lang
        array_push($error, "Model mag niet langer dan 20 tekens zijn.");
    }
    if (strlen($_POST['model']) < 1){
        //Model te kort
        array_push($error, "Voer een model in.");
    }
    else{
        //Maakt nette modelnaam
        $GLOBALS['model'] = ucfirst(strtolower($GLOBALS['mysqli']->real_escape_string($_POST['model'])));
    }

    //Checken of woonplaats niet langer is dan 30 teken
    if (strlen($_POST['plaats']) > 30){
        //woonplaats te lang
        array_push($error, "Plaats mag niet langer dan 30 tekens zijn");
    }
    if (strlen($_POST['plaats']) < 1){
        //Plaats te kort
        array_push($error, "Voer een plaats in.");
    }
    else{
        //maakt nette plaatsnaam
        $GLOBALS['plaats'] = ucfirst(strtolower($GLOBALS['mysqli']->real_escape_string($_POST['plaats'])));
    }

    //Checken of adres niet langer is dan 30 teken
    if (strlen($_POST['adres']) > 30){
        //woonplaats te lang
        array_push($error, "Adres mag niet langer dan 30 tekens zijn");
    }
    if (strlen($_POST['adres']) < 1){
        //Adres te kort
        array_push($error, "Voer een adres in.");
    }
    else{
        //maakt nette adres
        $GLOBALS['adres'] = ucfirst(strtolower($GLOBALS['mysqli']->real_escape_string($_POST['adres'])));
    }

    if (strlen($_POST['postcode']) < 1){
        //postcode te kort
        array_push($error, "Voer een postcode in.");
    }
    else{
        $remove = str_replace(" ","", $_POST['postcode']);
        $upper = strtoupper($remove);
        if( preg_match("/^\W*[1-9]{1}[0-9]{3}\W*[a-zA-Z]{2}\W*$/",  $upper)) {
            $GLOBALS['postcode'] = $GLOBALS['mysqli']->real_escape_string($upper);
        } else {
            array_push($error, "Voer een geldige postcode in.");
        }
    }

    if(is_numeric($_POST['versnellingen'])){
        $GLOBALS['versnellingen'] = $GLOBALS['mysqli']->real_escape_string($_POST['versnellingen']);
    }
    else{
        array_push($error, "Voer een geldig aantal versnellingen in.");
    }

    if(is_numeric($_POST['borg'])){
        $GLOBALS['borg'] = $GLOBALS['mysqli']->real_escape_string($_POST['borg']);
    }
    else{
        array_push($error, "Voer een geldig aantal borg in.");
    }

    if(is_numeric($_POST['huur-prijs'])){
        $GLOBALS['huurPrijs'] = $GLOBALS['mysqli']->real_escape_string($_POST['huur-prijs']);
    }
    else{
        array_push($error, "Voer een geldig huurprijs in.");
    }

    if(!empty($error)){
        for ($i = 0; $i < count($error); $i++){
            echo $error[$i] ."<br>";
        }
    }
    else{
        //geen fouten in input
        if (!$_FILES["foto"]["name"]){
            //geen foto
            $sql = "INSERT INTO fietsen
                    (borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen, model, omschrijving, postcode) 
                    VALUES (" . $borg . "," . $huurPrijs . ",'" . $_SESSION['id'] . "','" . $plaats . "'," . $_POST['soort_fiets'] . "," . $_POST['merk_naam'] . ",'" . $adres . "','','" . $_POST['geslacht_fiets'] . "','" . $_POST['kleur'] . "','" . $versnellingen . "','" . $model . "','".$omschrijving."','" . $postcode . "');";
            $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
            header('location: fiets.php?succesvol_toegevoegd='. urlencode('true'));
        }
        else{
            $uniekePad = date('dmYHis') .$_SESSION['id'];
            $target_dir = "fiets_afbeeldingen/" .$uniekePad;
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            $uploadOk = 1;
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
            if ($uploadOk == 0) {
                echo "Afbeelding niet geupload, probeer het opnieuw.";
            }
            else {// if everything is ok, try to upload file
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    //echo Is geupload
                    $sql = "INSERT INTO fietsen
                            (borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen, model, omschrijving, postcode) 
                            VALUES (".$borg.",".$huurPrijs.",'".$_SESSION['id']."','".$plaats."',".$_POST['soort_fiets'].",".$_POST['merk_naam'].",'".$adres."','$target_file','".$_POST['geslacht_fiets']."','".$_POST['kleur']."','".$versnellingen."','".$model."','".$omschrijving."','".$postcode."');";
                    $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
                    header('location: fiets.php?succesvol_toegevoegd='. urlencode('true'));
                }
                else {
                    echo "Afbeelding niet geupload, probeer het opnieuw.";
                }
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
                                <option value =<?php echo($row['id'])?><?php if(isset($_POST['merk_naam'])){ if($_POST['merk_naam']==($row['id'])){echo ' selected';}} ?>><?php echo($row['merk_naam']) ?></option><?php
                            }
                            ?>
                        </select><br></td></tr>
                <tr><td>Model(*)</td><td><input type="text" name="model" placeholder="Model" value="<?php echo (isset($_POST['model']) ? $_POST['model'] : ''); ?>" required></td></tr>
                <tr><td>Plaats(*)</td><td><input type="text" name="plaats" placeholder="Plaats" value="<?php echo (isset($_POST['plaats']) ? $_POST['plaats'] : ''); ?>" required></td></tr>
                <tr><td>Adres(*)</td><td><input type="text" name="adres" placeholder="Adres" value="<?php echo (isset($_POST['adres']) ? $_POST['adres'] : ''); ?>" required></td></tr>
                <tr><td>Postcode(*)</td><td><input type="text" name="postcode" placeholder="Postcode" value="<?php echo (isset($_POST['postcode']) ? $_POST['postcode'] : ''); ?>" required></td></tr>
                <tr><td>Kleur fiets(*):</td><td><select name="kleur">
                            <option value="Geel" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Geel'){echo ' selected';}} ?>>Geel</option>
                            <option value="Oranje" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Oranje'){echo ' selected';}} ?>>Oranje</option>
                            <option value="Zwart" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Zwart'){echo ' selected';}} ?>>Zwart</option>
                            <option value="Blauw" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Blauw'){echo ' selected';}} ?>>Blauw</option>
                            <option value="Grijs" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Grijs'){echo ' selected';}} ?>>Grijs</option>
                            <option value="Wit" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Wit'){echo ' selected';}} ?>>Wit</option>
                            <option value="Roze" <?php if(isset($_POST['kleur'])){ if($_POST['kleur'] == 'Roze'){echo ' selected';}} ?>>Roze</option>
                        </select></td></tr>
                <tr><td>Geslacht fiets(*)</td><td><input type="radio"  name="geslacht_fiets" value="Man" <?php if(isset($_POST['geslacht_fiets'])){ if($_POST['geslacht_fiets'] == 'Man'){echo ' checked="checked"';}} ?>>Mannen fiets
                        <input type="radio" name="geslacht_fiets" value="Vrouw" <?php if(isset($_POST['geslacht_fiets'])){ if($_POST['geslacht_fiets'] == 'Vrouw'){echo ' checked="checked"';}} ?>>Vrouwen fiets
                        <input type="radio" name="geslacht_fiets" value="Onzijdig"<?php if(isset($_POST['geslacht_fiets'])){ if($_POST['geslacht_fiets'] == 'Onzijdig'){echo ' checked="checked"';}} ?>>Onzijdige fiets</tr>
                <tr><td>Versnellingen(*)</td><td><input type="number" min="0" max="27" name="versnellingen" placeholder="Aantal versnellingen" value="<?php echo (isset($_POST['versnellingen']) ? $_POST['versnellingen'] : ''); ?>" required></td></tr>
                <tr><td>Soort fiets(*)</td><td><select name="soort_fiets">
                            <?php
                            $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
                            $result = $mysqli->query($sql);
                            while($row = $result->fetch_assoc()) {
                                ?>
                                <option value = <?php echo($row['id'])?><?php if(isset($_POST['soort_fiets'])){ if($_POST['soort_fiets']==($row['id'])){echo ' selected';}} ?>><?php echo($row['soort_fiets'])?></option><?php
                            }
                            ?>
                        </select></td></tr>
                <tr><td>Borg(*)</td><td><input type="number" min="0" step="0.01" name="borg" max="1000" placeholder="Borg" value="<?php echo (isset($_POST['borg']) ? $_POST['borg'] : ''); ?>" required></td></tr>
                <tr><td>Huurprijs per dag(*)</td><td><input type="number" step="0.01" min="0" max="200" name="huur-prijs" placeholder="Huurprijs" value="<?php echo (isset($_POST['huur-prijs']) ? $_POST['huur-prijs'] : ''); ?>" required></td></tr>
                <tr><td>Omschrijving</td><td><textarea style="resize: none;"name="omschrijving" rows="5" cols="50"><?php echo (isset($_POST['omschrijving']) ? $_POST['omschrijving'] : ''); ?></textarea></td></tr>
                <tr><td>Afbeelding:</td><td><input type="file" name="foto" value="foto" id="foto"></td></tr>
                <tr><td><input type="submit" name="toevoegen" value="Toevoegen"></td></tr>
            </table>
        </form>

    </body>
</html>