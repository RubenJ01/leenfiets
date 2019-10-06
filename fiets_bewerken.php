<?php
require 'utils/database_connection.php';
$borg = null;
$afbeelding = null;
$merk = null;
$model = null;
$plaats = null;
$adres = null;
$kleur_fiets = null;
$geslacht_fiets = null;
$versnellingen = null;
$soort_fiets = null;
$huurprijs_dag = null;

$sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam from fietsen, merk_fiets, soort_fiets
WHERE fietsen.id_soort_fiets = soort_fiets.id AND fietsen.id_merk_fiets = merk_fiets.id AND fietsen.id = 95 limit 1";

$query = $mysqli->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $borg = $row['borg'];
    $afbeelding = $row['foto'];
    $merk = $row['merk_naam'];
    $model = $row['model'];
    $plaats = $row['plaats'];
    $adres = $row['adres'];
    $kleur_fiets = $row['kleur_fiets'];
    $geslacht_fiets = $row['geslacht_fiets'];
    $versnellingen = $row['versnellingen'];
    $soort_fiets = $row['soort_fiets'];
    $huurprijs_dag = $row['prijs'];
}
?>
<html>
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
    <input type="radio" <?php if($geslacht_fiets == 'Man'){echo 'checked="checked';} ?> name="geslacht_fiets" value="Man">Mannen fiets
    <input type="radio" <?php if($geslacht_fiets == 'Vrouw'){echo 'checked="checked';} ?> name="geslacht_fiets" value="Vrouw" >Vrouwen fiets<br>
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
    <input type="file" name="foto" value="foto" id="foto"><br><br>
    <input type="submit" name="bewerken" value="Bewerken">
</form>
<img style="width: 20%;" src="<?php echo $afbeelding?>">
</body>
</html>
