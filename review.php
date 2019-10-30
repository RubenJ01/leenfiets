
<!DOCTYPE html>
<?php
include "menu.php";
include "utils/core_functions.php";
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Reviews</title>
  </head>
  <body>

 <?php

 if (isset($_POST['submit'])) {
     $sql = "INSERT INTO comments VALUES('','','')";
     $review_update = $mysqli->query($sql);
     header("Refresh:0");
 }
 //werkt nog niet
 ?>


  <form action="" method="post">
      <input type="text" name="name" value="Uw naam"><br>
      <textarea name="comment" cols="50" rows="5">Laat een review achter</textarea><br>
      <input type="submit" value="Laat review achter!">
  </form>



  </body>
</html>