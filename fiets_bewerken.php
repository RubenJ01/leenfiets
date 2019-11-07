<?php
require 'utils/database_connection.php';
include 'menu.php';

if (!isset($_SESSION)) {
    session_start();
}

$fiets_id = $_GET['fiets_id'];
$gebruiker_id  = $_SESSION['id'];

$sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.postcode, fietsen.omschrijving,fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
        from fietsen, merk_fiets, soort_fiets   
        WHERE fietsen.id_soort_fiets = soort_fiets.id 
        AND fietsen.id_merk_fiets = merk_fiets.id 
        AND fietsen.id = $fiets_id 
        AND fietsen.gebruiker_id = $gebruiker_id 
        limit 1 ";

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
        $GLOBALS['postcode'] = $row['postcode'];
        $GLOBALS['omschrijving'] = str_replace("<br>","\n",$row['omschrijving']);
    }
}
else {
    header('location: index.php');
}
if(isset($_POST['bewerken'])){
    $error = array();
    //Alle post data controleren en eventueel eenpassen
    $omschrijvingnieuw = $GLOBALS['mysqli']->real_escape_string(str_replace("\n","<br>",$_POST['omschrijving']));

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

    if(empty($_POST['geslacht_fiets'])){
        array_push($error, "Selecteer een geslacht.");
    }
    else{

    }

    if(!empty($error)){
        for ($i = 0; $i < count($error); $i++){
            echo $error[$i] ."<br>";
        }
    }
    else{
        $sql = "UPDATE fietsen SET postcode = '".$postcode."', borg = ".$borg.", prijs = ".$huurPrijs.", plaats = '".$plaats."', id_soort_fiets = ".$_POST['soort_fiets'].", id_merk_fiets = ".$_POST['merk_naam'].", adres = '".$adres."', geslacht_fiets = '".$_POST['geslacht_fiets']."', kleur_fiets = '".$_POST['kleur']."', versnellingen = '".$versnellingen."', model = '".$model."', omschrijving = '".$omschrijvingnieuw."' WHERE id = $fiets_id and gebruiker_id = $gebruiker_id ";
        $insert_query = $mysqli->query($sql);
        header("Location: fiets.php?fiets_id=$fiets_id");
        die();
    }
}

if(isset($_POST['foto_bewerken'])){
    if (empty($afbeelding)) {
        $uniekePad = date('dmYHis') .$gebruiker_id;
        $target_dir = "fiets_afbeeldingen/" .$uniekePad;
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            //is foto
            $uploadOk = 1;
        }
        else {
            //is geen foto
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            //echo "Niet geupload";
        }
        else {// if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                //uploaden gelukt
                $sql = "UPDATE fietsen SET foto = '$target_file' where id = $fiets_id and gebruiker_id = $gebruiker_id;";
                $insert_query = $mysqli->query($sql);
            }
            else {
                //echo "Uploaden niet gelukt";
            }
        }
    }
    else {
        $target_dir = "$afbeelding";
        $target_file = $target_dir ;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            //bestand is foto
            $uploadOk = 1;
        }
        else {
            // Bestand is geen foto
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            //echo "Niet geupload";
        }
        else {// if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // echo "Is geupload";
            }
            else {
                //echo "Uploaden niet gelukt";
            }
        }
    }
}

if(isset($_POST['verwijderen'])){
    if (empty($afbeelding)) {
        $sql = "DELETE FROM fietsen where id = $fiets_id and gebruiker_id = $gebruiker_id;";
        $delete_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
        header("Location: mijn_fietsen.php");
    }
    else{
        unlink($afbeelding);
        $sql = "DELETE FROM fietsen where id = $fiets_id and gebruiker_id = $gebruiker_id;";
        $delete_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
        header("Location: mijn_fietsen.php");
    }
}?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Fiets bewerken</title>
    <meta charset="UTF-8">
</head>
<body>
<h1 style="text-align: center">Fiets bewerken</h1>
    <div class="afbeelding_foto_bewerken">
        <img style="width: 300px;" src="<?php
            if (empty($afbeelding)) {
            echo 'fiets_afbeeldingen/default.png';
            }
            else{echo $afbeelding ."?t=" .time();}?>"><br>
        <h1>Afbeelding bewerken</h1><br>
        <form method="post" id="fietsenbewerken" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>?fiets_id=<?php echo $fiets_id?>">
            <input type="file" name="foto" value="foto" id="foto"><br>
            <input type="submit" name="foto_bewerken" value="Foto wijzigen">
        </form>
    </div>
    <form method="post" id="fietsenbewerken" enctype="multipart/form-data"  action="<?php echo $_SERVER['PHP_SELF'];?>?fiets_id=<?php echo $fiets_id?>">
        <table>
        <tr><td>Merk (*)</td><td><select name="merk_naam"><?php
                    $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
                    $result = $mysqli->query($sql);
                    while($row = $result->fetch_assoc()) {?>
                        <option value =<?php echo($row['id'])?><?php if($merk == ($row['merk_naam'])) {echo ' selected';}?>><?php echo($row['merk_naam'])?>
                        </option><?php
                    }?>
                </select></td></tr>
        <tr><td>Model(*)</td><td><input type="text" name="model" placeholder="Model" value="<?php echo $model?>" required></td></tr>
        <tr><td>Plaats(*)</td><td><input type="text" name="plaats" placeholder="Plaats" value="<?php echo $plaats?>" required></td></tr>
        <tr><td>Adres(*)</td><td><input type="text" name="adres" placeholder="Adres" value="<?php echo $adres?>" required></td></tr>
        <tr><td>Postcode(*)</td><td><input type="text" name="postcode" placeholder="Postcode" value="<?php echo $postcode?>" required></td></tr>
        <tr><td>Kleur fiets(*)</td><td><select name="kleur" >
                    <option value="Geel"<?php if($kleur_fiets == 'Geel'){echo 'selected';}?>>Geel</option>
                    <option value="Oranje"<?php if($kleur_fiets == 'Oranje'){echo 'selected';}?>>Oranje</option>
                    <option value="Zwart"<?php if($kleur_fiets == 'Zwart'){echo 'selected';}?>>Zwart</option>
                    <option value="Blauw"<?php if($kleur_fiets == 'Blauw'){echo 'selected';}?>>Blauw</option>
                    <option value="Grijs"<?php if($kleur_fiets == 'Grijs'){echo 'selected';}?>>Grijs</option>
                    <option value="Wit"<?php if($kleur_fiets == 'Wit'){echo 'selected';}?>>Wit</option>
                    <option value="Roze"<?php if($kleur_fiets == 'Roze'){echo 'selected';}?>>Roze</option>
                </select></td></tr>
        <tr><td>Geslacht fiets(*)</td><td><input type="radio"<?php if($geslacht_fiets == 'Man'){echo 'checked="checked"';}?>name="geslacht_fiets" value="Man">Mannen fiets
                <input type="radio"<?php if($geslacht_fiets == 'Vrouw'){echo 'checked="checked"';}?>name="geslacht_fiets" value="Vrouw" >Vrouwen fiets
                <input type="radio"<?php if($geslacht_fiets == 'Onzijdig'){echo 'checked="checked"';}?>name="geslacht_fiets" value="Onzijdig" >Onzijdige fiets<br></td></tr>
        <tr><td>Versnellingen(*):</td><td><input type="number" min="0" max="27" name="versnellingen" placeholder="Aantal versnellingen" value="<?php echo $versnellingen?>" required><br></td></tr>
        <tr><td>Soort fiets(*):</td><td><select name="soort_fiets"><?php
                    $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
                    $result = $mysqli->query($sql);
                    while($row = $result->fetch_assoc()) {?>
                        <option value =<?php echo($row['id'])?><?php if($soort_fiets == ($row['soort_fiets'])){echo ' selected';}?>><?php echo($row['soort_fiets'])?></option><?php
                    }?></select></td></tr>
        <tr><td>Borg(*)</td><td><input type="number" min="0" step="0.01" name="borg" max="1000" placeholder="Borg" value="<?=$borg?>" required></td></tr>
        <tr><td>Huurprijs per dag(*)</td><td><input type="number" step="0.01" min="0" max="200" name="huur-prijs" placeholder="Huurprijs" value="<?php echo $huurprijs_dag?>" required></td></tr>
        <tr><td>Omschrijving</td><td><textarea style="resize: none;"name="omschrijving" rows="5" cols="50"><?php echo $omschrijving?></textarea></td></tr>
        <tr><td><input type="submit" name="bewerken" value="Opslaan"></td><td><input type="submit" name="verwijderen" value="Verwijderen"></td></tr>
        </table>
    </form>
    </body>
</html>
