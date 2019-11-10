<!DOCTYPE html>
<?php
include "utils/database_connection.php";
include "menu.php";
if (!isset($_SESSION)) {
    session_start();
  }

  if(isset($_POST['verwijderen'])){
          $sql = "DELETE FROM nieuws where id={$_POST['id']}";
          $delete_query = $mysqli->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
      header("Location: nieuwspagina.php?verwijderd=true");
    }
?>
<html>
<title>Nieuws</title>
<meta charset="UTF-8">
<head>
<style>

.nieuwsbericht{
  border: 2px solid black;
    border-radius: 5px;
  background-color: #4CAF50;
  width: 80%;
  margin-left:auto;
  margin-right:auto;
    padding: 5px;
}
.nieuwstitel{
  font-size:24px;
}
.verwijderen{
  margin-left:auto;
  margin-right:auto;
}
</style>
<title>nieuws</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php
if(isset($_GET['toegevoegd'])){
    echo "<h5 style='text-align: center'>Je nieuwsbericht is toegevoegd op de website</h5>";
}
if(isset($_GET['verwijderd'])){
    echo "<h5 style='text-align: center'>Je nieuwsbericht is verwijderd van de website</h5>";
}
$sql_code = "SELECT g.naam, n.titel, n.beschrijving,
                         n.datum, n.schrijver, n.id, g.id as gebruiker
             FROM gebruiker g
             JOIN  nieuws n
             ON n.schrijver = g.id
             ORDER BY Datum DESC";
$result = $mysqli->query($sql_code);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<br><div class='nieuwsbericht'>
        <table><tr>
               <div class = 'nieuwstitel' style='text-align: center'>" ."<b>" .$row["titel"], "</b>","</div>".
               $row["beschrijving"], "<br><br>".
              "Schrijver: " . $row['naam'], "<br>".
               "Geschreven op ".$row["datum"],
       "</tr></table>","</div>";
        if (isset($_SESSION['rol'])) {
            if ($_SESSION['rol'] == 'admin') {
            echo "<div style= 'text-align:center;'><form method = 'post'><input type='submit' name='verwijderen' value='Verwijderen'> <input type='number' name='id' value='{$row['id']}' style='display:none;'> </form>"."<br>","</div>";
          }

    }
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
          <div style= "text-align:center;">titel</div>
        <br>
          <div style= "text-align:center;"><input type="text" name="titel" value=""></div>
        <br>
          <div style= "text-align:center;">beschrijving</div>
        <br>
          <div style= "text-align:center;"><textarea style="resize: none;"name="beschrijving" rows="5" cols="60"></textarea></div>
      <br>
      <div style= "text-align:center;"><input type="submit" name= "Verstuur"></div>
      </form>';
    }
  }
?>
</body>
</html>
<?php
if(isset($_POST["Verstuur"])){
    $nieuwTekst = str_replace("\n","<br>",$_POST['beschrijving']);
  $titel = NULL;
  $beschrijving = NULL;
  $schrijver = $_SESSION["id"];
    if(isset($_POST["titel"])&& $_POST["titel"] != ""){
      $titel = $_POST["titel"];

    }
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
    $sql = "INSERT INTO nieuws (schrijver,titel,beschrijving) VALUES ('$schrijver','$titel','$nieuwTekst')";

    if(!mysqli_query($mysqli,$sql)){
      echo 'niet toegevoegd';
    }
    else{
        header("Location: nieuwspagina.php?toegevoegd=true");
    }

  }
?>
