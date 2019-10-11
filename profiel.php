<?php
require 'utils/database_connection.php';

if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title><?php $id = $_GET['gebruikers_id']; ?></title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<?php
if (isset($_GET['gebruikers_id'])) {

    $sql_userinfo = "select naam, email from gebruiker where id = '$id'";
    $get_userinfo = $mysqli->query($sql_userinfo);

    while ($row = $get_userinfo->fetch_assoc()) {
        $naam = $row['naam'];
        $email = $row['email'];
    }
?>


    <p>Gebruikersnaam: <?php echo $naam?></p>
    <p>Email address: <?php echo $email?></p>
    <p>Gebruiker ID: <?php echo $id?></p>
<?php } else {
    //header('location: index.php');
} ?>
</body>
</html>
