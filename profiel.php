<?php
require 'utils/database_connection.php';

if (!isset($_SESSION)) {
    session_start();
}
?>

<?php
if (isset($_GET['gebruikers_id'])) {

$GLOBALS['id'] = $_GET['gebruikers_id'];
$sql_userinfo = "select naam, email from gebruiker where id = '$id'";
$get_userinfo = $mysqli->query($sql_userinfo);

while ($row = $get_userinfo->fetch_assoc()) {
    $naam = $row['naam'];
    $email = $row['email'];
}
?>

<?php } else {
    header('location: index.php');
} ?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <title><?php $GLOBALS['id'] = $_GET['gebruikers_id']; echo $naam;?></title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>

    <table>
        <tr><td>Profielfoto:</td><td><input type="file" name="foto" value="foto" id="foto"></td></tr>
        <tr><td><input type="submit" name="toevoegen" value="Toevoegen"></td></tr>
    </table>

    <p>Gebruikersnaam: <?php echo $naam?></p>
    <p>Email address: <?php echo $email?></p>
    <p>Gebruiker ID: <?php echo $id?></p>
    <p>Bio: </p><br/><br/>

    <p>Bio updaten:</p>
    <form action="" method="post">
        <textarea name="bio" cols="40" rows="5"></textarea><br/>
        <input type="submit" name="submit" value="Update en verzenden">
    </form>


</body>
</html>
