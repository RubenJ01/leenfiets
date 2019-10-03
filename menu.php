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
if(isset($_SESSION['email'])) {
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="utils/styles.css">
    <meta charset="UTF-8">
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="#">Nieuws</a>
    <div class="dropdown">
        <button class="dropbtn">Mijn account</button>
        <div class="dropdown-content">
            <a href="mijn_fietsen.php">Mijn fietsen</a>
            <a href="fiets_toevoegen.php">Fiets toevoegen</a>
            <a href="uitloggen.php">Uitloggen</a>
        </div>
    </div>
    <a href="#">Contact</a>
</div>
</body>
</html>
<?php } else { ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="utils/styles.css">
    <meta charset="UTF-8">
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="#">Nieuws</a>
    <div class="dropdown">
        <button class="dropbtn">Mijn account</button>
        <div class="dropdown-content">
            <a href="inloggen.php">Inloggen</a>
            <a href="registreren.php">Registreren</a>
        </div>
    </div>
    <a href="#">Contact</a>
</div>
</body>
</html>
<?php } ?>