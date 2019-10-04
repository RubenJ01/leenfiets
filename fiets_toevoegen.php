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
<form method="post" id="fietsentoevoegen" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Merk
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
        <option value="anders">Anders</option>
    </select><br>
    Model: <input type="text" name="model" placeholder="Model"><br>
    Plaats: <input type="text" name="plaats" placeholder="Plaats"><br>
    Adres: <input type="text" name="adres" placeholder="Adres"><br>
    Kleur fiets:
    <select name="kleur">
        <option value="Geel">Geel</option>
        <option value="Oranje">Oranje</option>
        <option value="Zwart">Zwart</option>
        <option value="Blauw">Blauw</option>
        <option value="Grijs">Grijs</option>
        <option value="Wit">Wit</option>
    </select><br>
    Man of vrouw<input type="radio" name="soort_fiets" value="Mannen fiets">Mannen fiets
    <input type="radio" name="soort_fiets" value="Vrouwen fiets">Vrouwen fiets<br>
    Versnellingen: <input type="number" name="merk" placeholder="Aantal versnellingen"><br>
    Soort fiets:
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
        <option value="anders">Anders</option>
    </select><br>
    Borg: <input type="number" name="borg" placeholder="Borg"><br>
    Huurprijs per dag:<input type="number" name="huur-prijs" placeholder="Huurprijs"><br>
    <input type="file" name="foto" value="foto"><br><br>
    <input type="submit" name="toevoegen" value="Toevoegen">
</form>
<?php
if(isset($_POST['toevoegen'])){
    $sql = "INSERT INTO fietsen(borg, prijs, gebruiker_id, plaats, id_soort_fiets, id_merk_fiets, adres, foto_id) VALUES (".$_POST['merk_naam'].",2,3,'".$_POST['plaats']."',".$_POST['soort_fiets'].",3,'".$_POST['adres']."',3);";
    $insert_query = $mysqli->query($sql);
}
?>
</body>
</html>