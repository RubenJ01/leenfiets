
<!DOCTYPE html>
<?php
include "menu.php";
require 'utils/database_connection.php';
require 'utils/core_functions.php';
if (!isset($_SESSION)) {
    session_start();
}
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Reviews</title>
  </head>
  <body>

 <?php



 ?>

 <div class='profiel_fiets_text'>
     <h2>Op deze pagina kunt u een review achterlaten over de wesite.<br> Wat vind u van de website?<br></h2>
 </div>



  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <input type="text" name="name" value="<?php echo (isset($_POST['name']) ? $_POST['name'] : ''); ?>" required placeholder="Uw naam"><br>
      <textarea name="comment" cols="50" rows="5" value="<?php echo (isset($_POST['comment']) ? $_POST['comment'] : ''); ?>" required placeholder="Review"></textarea><br>
      <input type="submit" name="submit" value="Laat review achter!">
  </form>

<?php

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
}

$sql = "INSERT INTO `comments`(`name`, `comment`) VALUES ('$name', '$comment')";
$result = $mysqli->query($sql);


?>


  </body>
</html>