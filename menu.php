<?php
/**
 * @file menu.php
 *
 * @brief hier wordt het menu gemaakt.
 *
 * Het menu hoeft maar één keer bewerkt te worden, dan wordt hij overal aangepast.
 */
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="/ict-project/utils/styles.css">
    <meta charset="UTF-8">
</head>
<body>
<div class="navbar">
    <a href="/ict-project/index.php">Home</a>
    <a href="#">Nieuws</a>
    <div class="dropdown">
        <button class="dropbtn">Mijn account</button>
        <div class="dropdown-content">
            <a href="/ict-project/fietsen_systeem/mijn_fietsen.php">Mijn fietsen</a>
            <a href="/ict-project/fietsen_systeem/fiets_toevoegen.php">Fiets toevoegen</a>
            <a href="#">Inloggen</a>
            <a href="/ict-project/registratie_systeem/registreren.php">Registreren</a>
        </div>
    </div>
    <a href="#">Contact</a>
</div>
</body>
</html>
