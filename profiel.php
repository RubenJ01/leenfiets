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

$user_bio = str_replace("\n","<br/>",$user_bio);

} else {
    header('location: index.php');
}

if (isset($_POST['submit'])) {
    $bio = str_replace("\n","<br/>",$_POST['bio']);
    $sql = "update gebruiker
            set bio = '$bio'
            where id = '$id'";
    $bio_update = $mysqli->query($sql);
    header("Refresh:0");
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
<form action="" method="post">
     <b>Profielfoto:</b><br/>
     <input type="file" name="foto" value="foto" id="foto"><br/>
     <input type="submit" name="toevoegen" value="Toevoegen">
</form>
<p><b>Gebruikersnaam: </b> <?php echo $naam;?></p>
<!-- <p><b>Email address: </b><?php echo $email;?></p>
<p><b>Gebruiker ID: </b> <?php echo $id;?></p> --!>
<p><b>Bio: </b></br> <?php echo $user_bio;?></p><br/><br/>
<?php
if (isset($_SESSION['id'])) {
    if ($_SESSION['id'] == $_GET['gebruikers_id']) {
        echo " 
    <p><b>Bio updaten:</b></p>
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

