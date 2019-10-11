<?php
require 'utils/database_connection.php';

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

} else {
    header('location: index.php');
}

if (isset($_POST['submit'])) {
    $bio = $_POST['bio'];
    $sql = "update gebruiker
            set bio = '$bio'
            where id = '$id'";
    $bio_update = $mysqli->query($sql);

}


?>

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
    </table><br/>

    <p>Gebruikersnaam: <?php echo $naam;?></p>
    <p>Email address: <?php echo $email;?></p>
    <p>Gebruiker ID: <?php echo $id;?></p>
    <p>Bio: <?php echo $user_bio;?></p><br/><br/>

<?php
if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $_GET['gebruikers_id']) {
        echo " 
    <p>Bio updaten:</p>
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

