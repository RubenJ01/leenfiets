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
    <link rel="stylesheet" href="utils/styles.css">
    <meta charset="UTF-8">
</head>
<body>
<div class="navbar">
    <a href="index.php"><img src="foto/foto_home.png" height="24" width="24"></a>
    <a href="#">Nieuws</a>
    <div class="dropdown">
        <button class="dropbtn">Mijn account</button>
        <div class="dropdown-content">
           <?php
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                echo "
                    <a href='fiets_toevoegen.php'>Fietsen toevoegen</a>
                    <a href='mijn_fietsen.php'>Mijn fietsen</a>
                    <a href='profiel.php?gebruiker=$email'>Mijn profiel</a>
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
    <a href="contact.php">Contact</a>
    <a href="faq.php">FAQ</a>
</div>
</body>
</html>
