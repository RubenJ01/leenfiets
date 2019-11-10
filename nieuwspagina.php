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
    }
?>
<html>
<head>
<style>

.nieuwsbericht{
  border: 2px solid black;
  background-color: #4CAF50;
  width: 80%;
  margin-left:auto;
  margin-right:auto;
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
$sql_code = "SELECT g.naam, n.titel, n.beschrijving,
                         n.datum, n.schrijver, n.id, g.id
             FROM gebruiker g
             JOIN  nieuws n
             ON n.schrijver = g.id
             ORDER BY Datum DESC";
$result = $mysqli->query($sql_code);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<br><div class='nieuwsbericht'>
        <table><tr>
               <div class = 'nieuwstitel'>". $row["titel"], "<br>","</div>".
               $row["beschrijving"], "<br>".
              "schrijver: " . $row['naam'], "<br>".
               $row["datum"],
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
$nieuwTekst = str_replace("\n","<br>",$_POST['beschrijving']);
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
    $sql = "INSERT INTO nieuws (schrijver,titel,beschrijving) VALUES ('$schrijver','$titel','$beschrijving')";

    if(!mysqli_query($mysqli,$sql)){
      echo 'niet toegevoegd';
    }
    else{
      echo 'toegevoegd';
    }

  }
?>
