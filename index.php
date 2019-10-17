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
    function filterMerk() {
        var x = document.getElementById("mySelect").value;
    }
</script>

<div><?php include 'menu.php'; ?></div>

<div id="tekstHoofdpagina">
    <h1>Welkom bij leenfiets</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec ex convallis, ultricies enim non, vestibulum dui. Fusce nec dui ac leo pharetra eleifend. Praesent lacus ante, gravida vitae purus id, dignissim egestas odio. Nulla in aliquet ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris sagittis mattis risus, a congue nulla aliquam ac. Donec ac ante fringilla, sollicitudin lorem non, interdum arcu. Vivamus tempor eget libero blandit fermentum. Proin consequat viverra felis sit amet dapibus. Sed at erat lacinia, dictum diam nec, pulvinar diam. Vestibulum pharetra volutpat rhoncus.</p>
</div>
<div id= "filter"><h1 style="">Filter</h1>
    Merk <select id = "mySelect" name="merk_naam" onchange ="filterMerk()"><?php
            $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
            $result = $mysqli->query($sql);
            while($row = $result->fetch_assoc())
            {
                ?>
                <option value =<?php echo($row['id'])?>><?php echo($row['merk_naam']) ?></option><?php
            }
            ?>
        </select>
    <p>WORK IN PROGRESS</p>
</div>
<div id= "bodyFietsen">
    <?php
    $sql = "SELECT fietsen.borg, fietsen.prijs, fietsen.versnellingen, fietsen.id, fietsen.plaats, fietsen.kleur_fiets, fietsen.model, fietsen.geslacht_fiets, fietsen.adres, fietsen.foto, soort_fiets.soort_fiets, merk_fiets.merk_naam 
                from fietsen, merk_fiets, soort_fiets   
                WHERE fietsen.id_soort_fiets = soort_fiets.id 
                AND fietsen.id_merk_fiets = merk_fiets.id ";
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
                    <img src = $afbeelding></div>
                    <a href=fiets.php?fiets_id=$row[id]  >Fiets bekijken</a>
                    </div>";
        }
    }
    else { echo "Er zijn momenteel geen fietsen beschikbaar"; }
    ?>
</div>
<button id="buttonLaadMeer" onclick="laadMeer()">Laad meer</button>
</body>
</html>
