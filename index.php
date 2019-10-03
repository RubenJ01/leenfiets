<?php
/**
 * @file index.php
 *
 * @brief Dit is de homepagina, hier worden de fietsen getoond.
 *
 * Gebruikers kunnen zoeken naar fietsen.
 */
if (!isset($_SESSION)) {
    session_start();
}
if(isset($_SESSION['email'])) {
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
</head>
<body>
    <div><?php include 'menu.php'; ?></div>
    <p>Je bent ingelogd!</p>
</body>
</html>
<?php } else { ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
</head>
<body>
    <div><?php include 'menu.php'; ?></div>
    <p>Je bent niet ingelogd!</p>
</body>
</html>
<?php } ?>
