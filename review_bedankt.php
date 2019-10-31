

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
     <h2><br>Bedankt voor uw review!<br>Klik <a href="index.php">hier</a> om terug te gaan naar de homepagina.<br></h2>
 </div>
    </body>
</html>