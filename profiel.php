<?php
require 'utils/database_connection.php';
include 'menu.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['gebruikers_id'])) {

$GLOBALS['id'] = $_GET['gebruikers_id'];
$sql_userinfo = "select naam, email, bio from gebruiker where id = '$id'";
$get_userinfo = $mysqli->query($sql_userinfo);

while ($row = $get_userinfo->fetch_assoc()) {
    $naam = $row['naam'];
    $email = $row['email'];
    $user_bio = $row['bio'];
}

$user_bio = str_replace("\n","<br/>",$user_bio);

} else {
    header('location: index.php');
}

if (isset($_POST['submit'])) {
    $bio = str_replace("\n","<br/>",$_POST['bio']);
    $sql = "update gebruiker
            set bio = '$bio'
            where id = '$id'";
    $bio_update = $mysqli->query($sql);
    header("Refresh:0");
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title><?php $GLOBALS['id'] = $_GET['gebruikers_id']; echo $naam;?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>


<br/>

<?php //tekst bezoeken profiel
if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $_GET['gebruikers_id']) {
        echo "
<div class='mijn_profiel_text'>
    <h2>
        Op deze pagina word u profiel weergegeven!<br/>
        U kunt hier uw bio updaten.
    </h2>
</div>";
        }
}
echo "<br>";
//Knoppen
if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $_GET['gebruikers_id']) {
        echo "
   <div class=\"knoppen\" style=\"text-align:center;\">
        <button class=\"knop\"><a class=\"linkpro\" href=\"index.php\" color=\"white\">Mijn Fietsen bekijken</a></button>
        <button class=\"knop\"><a class=\"linkpro\"  href=\"fiets_toevoegen.php\">Fiets toevoegen</a></button>
        <button class=\"knop\"><a class=\"linkpro\"  href=\"geld.php\">Mijn geld</a></button>
        <button class=\"knop\"><a class=\"linkpro\"  href=\"wachtwoord_vergeten.php\">Wachtwoord wijzigen</a></button>
        <button class=\"knop\"><a class=\"linkpro\"  href=\"uitloggen.php\">Uitloggen</a></button>
    </div>";
    }
}
?>

<br><br>


<div class="mijn_profiel_text_2">
    <h2 align="center"><b>Gebruikersnaam: </b> <?php echo $naam;?></h2>
    <h2 align="center"><b>Bio: </b> <?php echo $user_bio;?></h2><br/><br/>
</div>

<div class="formpje">
<?php //bio
if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $_GET['gebruikers_id']) {
        echo "<hr>
    <p align='center'><b>Bio updaten:</b></p>
    <form action='' method='post'>
        <textarea name='bio' cols='40' rows='5'></textarea><br/>
        <input type='submit' name='submit' value='Update en verzenden'>
    </form>
  ";
    }
}



?>





</body>
</html>
