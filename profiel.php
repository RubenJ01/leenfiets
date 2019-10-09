<?php
require 'utils/database_connection.php';

if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Profiel</title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
<?php
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];

    $sql_userinfo = "select naam from gebruiker where id = '$id'";
    $get_userinfo = $mysqli->query($sql_userinfo);

    while ($row = $get_userinfo->fetch_assoc()) {
        $naam = $row['naam'];
    }
    echo $email . "<br />";
    echo $naam .  "<br />";;
    echo $id . "<br />";;
?>
<?php } else { ?>

<?php } ?>
</body>
</html>
