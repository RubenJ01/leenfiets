<?php
/**
 * @file menu.php
 *
 * @brief hier wordt het menu gemaakt.
 *
 * Het menu hoeft maar één keer bewerkt te worden, dan wordt hij overal aangepast.
 */
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="icon" href="foto/icon.png" />
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
</head>
<body>
<div class="navbar">
    <a href="index.php"><img src="foto/foto_home.png" height="24" width="24"></a>
    <a href="nieuwspagina.php">Nieuws</a>
    <div class="dropdown">
        <button class="dropbtn">Mijn account</button>
        <div class="dropdown-content">
           <?php
            if (isset($_SESSION['id'])) {
                $id = $_SESSION['id'];
                if ($_SESSION['rol'] == 'admin') {
                    echo "
                        <a href='dashboard.php'>Dashboard</a>
                        ";
                }
                echo "
                    <a href='profiel.php?gebruikers_id=$id'>Mijn profiel</a>
                    <a href='mijn_fietsen.php'>Mijn fietsen</a>
                    <a href='fiets_toevoegen.php'>Fietsen toevoegen</a>
                    <a href='leen_verzoeken.php'>Leen verzoeken</a>
                    <a href='geld.php'>Geld ophalen/toevoegen</a>
                    <a href='uitloggen.php'>Uitloggen</a>
                    ";
            } else {
                echo "
                    <a href='inloggen.php'>Inloggen</a>
                    <a href='registreren.php'>Registreren</a>
                     ";
            }
           ?>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn">Contact</button>
        <div class="dropdown-content">
            <a href="contactgegevens.php">Social Media</a>
            <a href="contact.php">Stel ons een vraag</a>
            <a href='review.php'>Reviews</a>
        </div>
    </div>

    <a href="faq.php">FAQ</a>
</div>
</body>
</html>
