
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

  <div class='profiel_fiets_text'>
      <h2>Op deze pagina kunt u een review achterlaten over de wesite.<br> Wat vind u van de website?<br></h2>
  </div>
  <br>

  <div class="formpje">
     <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
         <input type="text" name="name" value="<?php echo (isset($_POST['name']) ? $_POST['name'] : ''); ?>" required placeholder="Uw naam"><br>
         <textarea name="comment" cols="50" rows="5" value="<?php echo (isset($_POST['comment']) ? $_POST['comment'] : ''); ?>" required placeholder="Review"></textarea><br>
         <input type="submit" name="submit" value="Laat review achter!">
     </form>
  </div>
  <br><br>


  <?php

  if(isset($_POST['submit'])) {
      $name = $_POST['name'];
      $comment = $_POST['comment'];
      $sql = "INSERT INTO comments(`name`, `comment`) VALUES ('$name', '$comment')";
      $result = $mysqli->query($sql);
      header("location: review.php");
  }

    $sql_result ="SELECT `name`, `comment` FROM `comments` WHERE 1 order by id DESC";
    $result_comment = $mysqli->query($sql_result);

    while ($row = $result_comment->fetch_assoc()){
        $comment_name = $row['name'];
        $comment = $row['comment'];
        $date = date("Y/m/d");
        echo "<div class='comment'><p><b>{$comment_name} op <b>{$date}</b></b> - <i>{$comment}</i><br><br></p></div>";
    }
?>
  </body>
</html>