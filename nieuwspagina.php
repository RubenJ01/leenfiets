<!DOCTYPE html>
<?php
include "utils/database_connection.php";
include "menu.php";
if (!isset($_SESSION)) {
    session_start();
  }
?>
<html>
<head>
<title>nieuws</title>
<link rel="stylesheet" href="./utils/styles.css">
</head>
<body>

<?php
$sql = "SELECT * FROM nieuws";
$result = mysqli_query($mysqli, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='nieuwsbericht'> titel: " . $row["titel"]. " - beschrijving: " . $row["beschrijving"]. "schrijver: " . $row["schrijver"]. "datum: " . $row["datum"]."</div><br>";
    }
  }
    else {
    echo "0 results";
}
?>
<body>
<?php
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] == 'admin') {
      echo '<form method = "POST">
        titel
        <br>
        <input type="text" name="titel" value="">
        <br>
        beschrijving
        <br>
        <textarea style="resize: none;"name="beschrijving" rows="5" cols="60"></textarea>
      <br>
      <input type="submit" name= "Verstuur">
      <body> <input type="submit" name="verwijderen" value="Verwijderen"> <body>
      </form>';
    }
  }
?>
<div class = "nieuwsartikel">
<div class = "nieuwstitel">
</div>
<div class = "nieuwsomschrijving">
</div>
</div>
</body>
</html>
<?php
if(isset($_POST["Verstuur"])){
  $titel = NULL;
  $beschrijving = NULL;
  $schrijver = $_SESSION["id"];
    if(isset($_POST["titel"])&& $_POST["titel"] != ""){
      $titel = $_POST["titel"];

    }}
    else{
      echo "vul een titel in";
      return;
    }
    if(isset($_POST["beschrijving"])&& $_POST["beschrijving"] != ""){
      $beschrijving = $_POST["beschrijving"];

    }
    else{
      echo "vul een beschrijving in";
      return;
    }
    $sql = "INSERT INTO nieuws (schrijver,titel,beschrijving) VALUES ('$schrijver','$titel','$beschrijving')";

    if(!mysqli_query($mysqli,$sql)){
      echo 'niet toegevoegd';
    }
    else{
      echo 'toegevoegd';
    }
    if(isset($_POST['verwijderen'])){
            $sql = "DELETE FROM nieuws where schrijver = $schrijver and titel = $titel and beschrijving = $beschrijving";
            $delete_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
            header("Location: nieuwspagina.php");
          }

    echo $schrijver, $titel, $beschrijving;
