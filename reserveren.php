<?php include 'menu.php';?>

<form class="" action="index.html" method="post">
  
</form>

<?php
require_once "utils/core_functions.php";
require_once "utils/database_connection.php";

if (isset($_GET['fiets_id']) === false) {
  RedirectToPage("index.php");
}
if (isset($_POST['collectionDate']) === false) {
  RedirectToPage("fiets.php?fiets_id={$_GET['fiets_id']}");
}
else {
  $collectionDate = $_POST['collectionDate'];
  $returnDate = $_POST['returnDate'];
  echo "Collectiondate = $collectionDate<br>";
  echo "ReturnDate = $returnDate";
}
?>
