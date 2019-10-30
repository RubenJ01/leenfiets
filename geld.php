<?php
  include "menu.php";
  include "utils/process_geld.php";

  // Start de sessie mocht dat nog niet gedaan zijn
  if (isset($_SESSION) === false) {
      session_start();
  }
  // Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
  if (isset($_SESSION['id']) === false) {
    RedirectToPage("inloggen.php");
  }

  $userId = $_SESSION['id'];
  $money = GetMoney($userId);
  $borrowingCosts = GetBorrowingCosts($userId);
  $depositCosts = GetDepositCosts($userId);
  $spendableMoney = GetSpendableMoney($userId);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form action="utils/process_geld.php" method="post">
      Vul hier een bedrag in dat u wilt toevoegen of ophalen:<br>
      <input class='currencyTextBox' name='geld' value="">
      <input type='submit' name='toevoegen' value='toevoegen'>
      <input type='submit' name='ophalen' value='ophalen'>
    </form>
    <!-- Het input filters script helpt mij om ervoor te zorgen dat de gebruiker alleen geldige bedragen kunnen invullen -->
    <script type="text/javascript" src="js/inputFilter.js"></script>

    Totaal geld: <?php echo $money; ?> <br>
    Leenkosten voor alle fietsen: <?php echo $borrowingCosts ?> <br>
    Borg voor alle fietsen: <?php echo $depositCosts; ?> <br>
    Beschikbaar geld(Om op te halen of om meer fietsen mee te lenen): <?php echo $spendableMoney; ?> <br>

    <img src="foto/betaal.jpeg" class="center1" ></a>

    <img src="foto/betaal.jpeg" class="center1" ></a>

  </body>
</html>
