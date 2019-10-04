<?php
/**
 * @file fiets_toevoegen.php
 *
 * @brief Gebruikers kunnen hier hun fiets toevoegen.
 *
 * Gebruikers kunnen hier hun fiets toevoegen.
 */
require 'utils/database_connection.php';
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
    </select><br>
    Man of vrouw(*)<input type="radio" checked="checked" name="geslacht_fiets" value="Man">Mannen fiets
    <input type="radio" name="geslacht_fiets" value="Vrouw">Vrouwen fiets<br>
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
    $sql = "INSERT INTO fietsen(borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto, geslacht_fiets, kleur_fiets, versnellingen) VALUES (".$_POST['borg'].",".$_POST['huur-prijs'].",3,'".$_POST['plaats']."',".$_POST['soort_fiets'].",".$_POST['merk_naam'].",'".$_POST['adres']."','/image/test.png','".$_POST['geslacht_fiets']."','".$_POST['kleur']."','".$_POST['versnellingen']."');";
    echo $sql;
    $insert_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);;
    }
?>
</body>
</html>