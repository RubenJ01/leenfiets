<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
        <head>
        <title>Fiets</title>
        <meta charset="UTF-8">
        </head>
    <body>
    <div><?php include 'menu.php'; ?></div>
    <?php
    if(isset($_GET['succesvol_toegevoegd'])){
        echo "<p>Je fiets is succesvol toegevoegd.</p>";
    }
    ?>
    </body>
</html>

