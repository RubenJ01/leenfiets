<?php
require 'utils/database_connection.php';

if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title><?php $email = $_GET['gebruiker']; echo $email; ?></title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<?php
if (isset($_GET['gebruiker'])) {
    $email = $_GET['gebruiker'];
    $sql_userinfo = "select naam, id from gebruiker where email = '$email'";
    $get_userinfo = $mysqli->query($sql_userinfo);

    while ($row = $get_userinfo->fetch_assoc()) {
        $naam = $row['naam'];
        $id = $row['id'];
    }
?>


    <p>Gebruikersnaam: <?php echo $naam?></p>
    <p>Email address: <?php echo $email?></p>
    <p>Gebruiker ID: <?php echo $id?></p>
<?php } else {
    header('location: index.php');
} ?>
</body>
</html>
