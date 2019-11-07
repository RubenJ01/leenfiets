<?php
include "menu.php";
require_once "utils/qrcode.php";
require_once "utils/core_functions.php";

if (!isset($_SESSION)) {
    session_start();
}
// Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
if (isset($_SESSION['id']) === false) {
  RedirectToPage("inloggen.php");
}
// Kijk of er een leen_verzoek in de getter staat
if (isset($_GET['leen_verzoek']) === false || isset($_GET['token']) === false) {
  RedirectToPage("leen_verzoeken.php");
}
else {// Alleen de eigenaar mag zijn eigen qr codes bekijken
  $query = "SELECT IF(EXISTS(
              SELECT l.id
              FROM leen_verzoek l
              JOIN fietsen f ON f.id = l.fiets_id
              WHERE l.id = ? AND l.token = ? AND f.gebruiker_id = ?
            ), 1, 0) AS bool";
  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('isi', $_GET['leen_verzoek'], $_GET['token'], $_SESSION['id']);
    if (!$stmt->execute()) {
      trigger_error($stmt->error, E_USER_ERROR);
    }
    $stmt->bind_result($b);
    $stmt->fetch();
    $stmt->close();
    // Als b(boolean) false is dan betekent dat dat iemand probeert de qr code te bekijken die niet de eigenaar van de fiets is
    if ($b === false) {
      RedirectToPage("leen_verzoeken.php");
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div class="wrapper">
      Hoe werkt het?<br>
      Stap1: Laat de qr code scannen door de lener vervolgens wordt het geld overgemaakt naar uw account ook kunt u de code sturen naar de lener deze kan hij vervolgens zelf invoeren om de fiets te lenen. <br>
      Stap2: Wanneer de lener de fiets heeft teruggebracht laat hem opnieuw een qr code scannen (of laat de lener weer zelf de code invullen) zodat de fiets weer gemarkeerd staat als teruggebracht in het systeem. De lener krijgt dan vervolgens zijn borg terug.<br>
      <br>
      Neem <a href="contact.php">contact</a> met ons op in de volgende gevallen:<br>
      - Mocht er schade zijn gemaakt aan de fiets, dan kijken wij hoe erg de schade is en krijg je een gedeelte van de borg die de lener bij ons heeft staan.<br>
      - Mocht de fiets gestolen zijn neem contact met ons op zodat wij dit kunnen uitzoeken. En als die daadwerkelijk gestolen is krijgt u de volledige borg van ons.<br>

      <h2 style="text-align: center;">CODE: <?php echo $_GET['token']; ?></h2>

      <img src="qr/<?php echo ($_GET['leen_verzoek'].'_'.$_GET['token']) ?>.svg" style=" display: block;margin-left: auto;margin-right: auto;width:50%;">
    </div>
  </body>
</html>
