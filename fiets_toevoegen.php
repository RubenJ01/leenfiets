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
    Merk: <input type="text" name="merk" placeholder="Merk"><br>
    Model: <input type="text" name="model" placeholder="Model"><br>
    Plaats: <input type="text" name="plaats" placeholder="Plaats"><br>
    Kleur fiets:
    <select name="merk">
        <option value="geel">Geel</option>
        <option value="oranje">Oranje</option>
        <option value="zwart">Zwart</option>
        <option value="blauw">Blauw</option>
        <option value="grijs">Grijs</option>
        <option value="wit">Wit</option>
    </select><br>
    Man of vrouw<input type="radio" name="soort_fiets" value="Mannen fiets">Mannen fiets
    <input type="radio" name="soort_fiets" value="Vrouwen fiets">Vrouwen fiets<br>
    Versnellingen: <input type="number" name="merk" placeholder="Aantal versnellingen"><br>
    Soort fiets:
    <select name="merk">
        <?php
        $sql = "SELECT soort_fiets FROM soort_fiets order by soort_fiets asc";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc())
        {
        ?>
        <option value = "<?php echo "`Aantal cilinders` = " .($row['soort_fiets'])?>" >
            <?php echo($row['soort_fiets']) ?>
        </option>
            <?php
        }
        ?>
    </select><br>
    Borg: <input type="number" name="borg" placeholder="Borg"><br>
    Huurprijs per dag:<input type="number" name="huur-prijs" placeholder="Huurprijs"><br>
    <input type="file" name="foto" value="foto"><br>
    <input type="submit" value="Toevoegen">
</form>
</body>
</html>