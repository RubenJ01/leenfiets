<?php
/**
 * @file index.php
 *
 * @brief Dit is de homepagina, hier worden de fietsen getoond.
 *
 * Gebruikers kunnen hier zoeken naar fietsen.
 */
if (!isset($_SESSION)) {
    session_start();
}
if(isset($_GET['sorteren_value'])) {


    $sorteren = $_GET['sorteren_value'];
}
else{
    $sorteren = "order by datum desc";
}
require 'utils/database_connection.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
</head>
<body class="hoofdpagina">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function(){
        $(".fiets_blok").slice(0, 3).show(); // pakt de eerste 3 fietsen
        $("#buttonLaadMeer").click(function(e){
            e.preventDefault();
            if($(".fiets_blok:hidden").length == 0){ // kijkt of er nog fietsen zijn
                alert("Er zijn geen fietsen meer");
            }
            $(".fiets_blok:hidden").slice(0, 6).show(); // Pak 6 nieuwe fietsen
        });
    });
    function laadMeer() {
        document.getElementById("tekstHoofdpagina").style.display = "none";
        document.getElementById("filter").style.display = "block";
        document.getElementById("bodyFietsen").style.width = "80%";
         }

    function clearform()
    {
        document.getElementById("merk_naam").selectedIndex = "0";
        document.getElementById("soort_fiets").selectedIndex = "0";
        document.getElementById("geslacht_fiets").selectedIndex = "0";
        document.getElementById("kleur").selectedIndex = "0";
        document.getElementById("min_versnelling").value="";
        document.getElementById("max_versnelling").value="";
        document.getElementById("min_prijs").value="";
        document.getElementById("max_prijs").value="";
        document.getElementById("plaats").value="";
        document.getElementById("ophaalDatum").value="";
        document.getElementById("terugDatum").value="";

    }
</script>

<div><?php include 'menu.php'; ?></div>
<div id="tekstHoofdpagina">
    <a href="index.php"><img src="foto/leenfiets_logo.jpeg" class="center" ></a>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec ex convallis, ultricies enim non, vestibulum dui. Fusce nec dui ac leo pharetra eleifend. Praesent lacus ante, gravida vitae purus id, dignissim egestas odio. Nulla in aliquet ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris sagittis mattis risus, a congue nulla aliquam ac. Donec ac ante fringilla, sollicitudin lorem non, interdum arcu. Vivamus tempor eget libero blandit fermentum. Proin consequat viverra felis sit amet dapibus. Sed at erat lacinia, dictum diam nec, pulvinar diam. Vestibulum pharetra volutpat rhoncus.</p>
</div>
<div id= "filter"><h3 style="margin-top: 0px; margin-bottom: 0px;">Filter</h3>
    <form method="get" id="fietsentoevoegen" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <b>Merk</b><br> <select style="width: 95%" id = "merk_naam" name="merk_naam"><?php
            $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
            $result = $mysqli->query($sql);
        ?><option selected  value="0"> Alle merken </option><?php
            while($row = $result->fetch_assoc())
            {
                ?>
                <option value =<?php echo($row['id'])?><?php if(isset($_GET['merk_naam'])){ if($_GET['merk_naam']==($row['id'])){echo ' selected';}} ?>><?php echo($row['merk_naam']) ?></option><?php
            }
            ?>
        </select>
    <b>Soort fiets</b><br><select style="width: 95%" id="soort_fiets" name="soort_fiets">
            <?php
            $sql = "SELECT soort_fiets, id FROM soort_fiets order by soort_fiets asc";
            $result = $mysqli->query($sql);
            ?><option selected  value="0"> Alle soorten </option><?php
            while($row = $result->fetch_assoc()) {
                ?>

                <option value = <?php echo($row['id'])?><?php if(isset($_GET['soort_fiets'])){ if($_GET['soort_fiets']==($row['id'])){echo ' selected';}} ?>><?php echo($row['soort_fiets'])?></option><?php
            }
            ?>
        </select>
    <b>Geslacht fiets</b><br>
    <select name="geslacht_fiets" id="geslacht_fiets" style="width: 95%">
        <option selected  value="0"> Alle geslachten </option>
        <option  value="Man" <?php if(isset($_GET['geslacht_fiets'])){ if($_GET['geslacht_fiets'] == 'Man'){echo ' selected';}} ?>>Mannen fiets</option>
        <option  value="Vrouw" <?php if(isset($_GET['geslacht_fiets'])){ if($_GET['geslacht_fiets'] == 'Vrouw'){echo ' selected';}} ?>>Vrouwen fiets </option>
        <option  value="Onzijdig" <?php if(isset($_GET['geslacht_fiets'])){ if($_GET['geslacht_fiets'] == 'Onzijdig'){echo ' selected';}} ?>>Onzijdige fiets</option>
    </select>
    <br><b>Kleur fiets</b><br><select style="width: 95%" name="kleur" id="kleur">
            <option selected  value="0"> Alle kleuren </option>
            <option value="Geel" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Geel'){echo ' selected';}} ?>>Geel</option>
            <option value="Oranje" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Oranje'){echo ' selected';}} ?>>Oranje</option>
            <option value="Zwart" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Zwart'){echo ' selected';}} ?>>Zwart</option>
            <option value="Blauw" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Blauw'){echo ' selected';}} ?>>Blauw</option>
            <option value="Grijs" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Grijs'){echo ' selected';}} ?>>Grijs</option>
            <option value="Wit" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Wit'){echo ' selected';}} ?>>Wit</option>
            <option value="Roze" <?php if(isset($_GET['kleur'])){ if($_GET['kleur'] == 'Roze'){echo ' selected';}} ?>>Roze</option>
        </select><br>
        <b>Versnellingen</b><br>
        Van <input style="width: 25%;" min="0" type="number" id="min_versnelling" name="min_versnelling" value="<?php echo (isset($_GET['min_versnelling']) ? $_GET['min_versnelling'] : ''); ?>">
        tot <input style="width: 25%;" min="0" type="number" id="max_versnelling" name="max_versnelling" value="<?php echo (isset($_GET['max_versnelling']) ? $_GET['max_versnelling'] : ''); ?>">
        <br><b>Prijs</b><br>
        Van <input style="width: 25%;" min="0" type="number" id="min_prijs" name="min_prijs" value="<?php echo (isset($_GET['min_prijs']) ? $_GET['min_prijs'] : ''); ?>">
        tot <input style="width: 25%;" min="0" type="number" id="max_prijs" name="max_prijs" value="<?php echo (isset($_GET['max_prijs']) ? $_GET['max_prijs'] : ''); ?>">
        <br><b>Plaats</b><br>
        <input type="text" style="width: 95%" name="plaats" id="plaats" value="<?php echo (isset($_GET['plaats']) ? $_GET['plaats'] : ''); ?>"><br>
    <b>Datum</b><br>
        <table style="text-align: left">
            <tr><td>Ophaal</td><td> <input type="date" id="ophaalDatum" name="ophaalDatum" placeholder="jjjj-mm-dd" value="<?php echo (isset($_GET['ophaalDatum']) ? $_GET['ophaalDatum'] : ''); ?>"></td></tr>
            <tr><td>Terugbreng</td><td><input type="date" id="terugDatum" placeholder="jjjj-mm-dd" name="terugDatum" value="<?php echo (isset($_GET['terugDatum']) ? $_GET['terugDatum'] : ''); ?>"><br></td></tr>

        </table>

        <b>Sorteren</b><br> <select name="sorteren_value">
            <option value="order by datum desc" <?php if(isset($_GET['sorteren_value'])){ if($_GET['sorteren_value'] == 'order by datum desc'){echo ' selected';}} ?>>Nieuwste</option>
            <option value="order by datum asc" <?php if(isset($_GET['sorteren_value'])){ if($_GET['sorteren_value'] == 'order by datum asc'){echo ' selected';}} ?>>Oudste</option>
            <option value="order by fietsen.prijs asc" <?php if(isset($_GET['sorteren_value'])){ if($_GET['sorteren_value'] == 'order by fietsen.prijs asc'){echo ' selected';}} ?>>Prijs oplopend</option>
            <option value="order by fietsen.prijs desc" <?php if(isset($_GET['sorteren_value'])){ if($_GET['sorteren_value'] == 'order by fietsen.prijs desc'){echo ' selected';}} ?>>Prijs aflopend</option>
        </select>
        <br>
    <input type="submit" name="reset" ONCLICK="clearform();" value="Reset">
        <input type="submit" name="filter" value="Filter">
</form>
</div>

<div id= "bodyFietsen">
    <?php
    if(isset($_GET['filter'])){
        echo "<script> laadMeer(); </script>";
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.id, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
                from fietsen, merk_fiets, soort_fiets   
                WHERE fietsen.id_soort_fiets = soort_fiets.id 
                AND fietsen.id_merk_fiets = merk_fiets.id
                ";
        if(!empty($_GET['merk_naam'])){
            $sql .= " AND fietsen.id_merk_fiets = " .$_GET['merk_naam'];
        }
        if(!empty($_GET['soort_fiets'])){
            $sql .= " AND fietsen.id_soort_fiets = " .$_GET['soort_fiets'];
        }
        if(!empty($_GET['geslacht_fiets'])){
            $sql .= " AND fietsen.geslacht_fiets = '" .$_GET['geslacht_fiets'] ."'";
        }
        if(!empty($_GET['kleur'])){
            $sql .= " AND fietsen.kleur_fiets = '" .$_GET['kleur'] ."'";
        }
        if(!empty($_GET['min_versnelling'])){
            $sql .= " AND fietsen.versnellingen >= " .$_GET['min_versnelling'];
        }
        if(!empty($_GET['max_versnelling'])){
            $sql .= " AND fietsen.versnellingen <= " .$_GET['max_versnelling'];
        }
        if(!empty($_GET['min_prijs'])){
            $sql .= " AND fietsen.prijs >= " .$_GET['min_prijs'];
        }
        if(!empty($_GET['max_prijs'])){
            $sql .= " AND fietsen.prijs <= " .$_GET['max_prijs'];
        }
        if(!empty($_GET['plaats'])) {
            $sql .= " AND fietsen.plaats LIKE '" . $_GET['plaats'] . "'";
        }
        if(!empty($_GET['ophaalDatum'])) {
        }
        if(!empty($_GET['terugDatum'] && $_GET['ophaalDatum'])) {
            $sql .= "   AND
                        fietsen.id not in (
                        select fiets_id 
from leen_verzoek
where
	(CAST(terugbreng_moment AS DATE) between '".$_GET['ophaalDatum'] ."' AND '".$_GET['terugDatum'] ."'
	AND status_ = 'gereserveerd')
  
	or (CAST(ophaal_moment AS DATE) between '".$_GET['ophaalDatum'] ."' AND '".$_GET['terugDatum'] ."'
	AND status_ = 'gereserveerd')
    
    or ('".$_GET['ophaalDatum'] ."' between CAST(ophaal_moment AS DATE) and CAST(terugbreng_moment AS DATE)  
	AND status_ = 'gereserveerd')
    
    or ('".$_GET['terugDatum'] ."' between CAST(ophaal_moment AS DATE) and CAST(terugbreng_moment AS DATE)  
	AND status_ = 'gereserveerd'))";
        }
        $sql .= $sorteren ;
            }
    else{
        $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.id, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
                from fietsen, merk_fiets, soort_fiets   
                WHERE fietsen.id_soort_fiets = soort_fiets.id 
                AND fietsen.id_merk_fiets = merk_fiets.id 
                 $sorteren";
    }
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        //maakt blokjes van sql
        while($row = $result->fetch_assoc()) {
            $afbeelding = $row['foto'];
            if (empty($afbeelding)){
                $afbeelding = "fiets_afbeeldingen/default.png";
            }
            echo   "<div class='fiets_blok'>
                    <h1>$row[merk_naam] $row[model]</h1>
                    <h4>€$row[prijs] per dag<br/></h4>
                    <ul>
                    <li>Plaats: $row[plaats]</li>
                    <li>Soort fiets: $row[soort_fiets]</li>
                    <li>Aantal versnellingen $row[versnellingen]</li>
                    <li>Geslacht fiets: $row[geslacht_fiets]</li>
                    </ul><div class='afbeeldingDiv'>
                    <img src = $afbeelding?t=time()></div>
                    <a href=fiets.php?fiets_id=$row[id]  >Fiets bekijken</a>
                    </div>";
        }
    }
    else { echo "Er zijn momenteel geen fietsen beschikbaar"; }

    if(isset($_GET['reset'])){
        echo "<script> laadMeer(); </script>";
     }

    if(isset($_GET['sorteren'])){
        echo "<script> laadMeer(); </script>";
    }
    ?>
</div>
<button id="buttonLaadMeer" onclick="laadMeer()">Laad meer</button>
</body>
</html>


