<?php

include 'menu.php';
require_once "utils/core_functions.php";
require_once "utils/database_connection.php";

// Start de sessie mocht dat nog niet gedaan zijn
if (isset($_SESSION) === false) {
    session_start();
}
// Kijk of de gebruiker is ingelogt anders ga terug naar de hoofdpagina
if (isset($_SESSION['id']) === false) {
  RedirectToPage("inloggen.php");
}

// Check of er een fiets id in de getter staat
if (isset($_GET['fiets_id']) === false) {
  RedirectToPage("index.php");
}
// Check of de gebruiker wel een datum heeft geselecteert
if (isset($_GET['collectionDate']) === false || isset($_GET['returnDate']) === false || $_GET['collectionDate'] == "Selecteer een datum") {
  RedirectToPage("fiets.php?fiets_id={$_GET['fiets_id']}");
}

// Haal wat data op over de fiets
$fietsId = $_GET['fiets_id'];
$query = "SELECT f.id, f.borg, f.prijs, f.gebruiker_id, f.foto, g.naam
          FROM fietsen f
          JOIN gebruiker g ON f.gebruiker_id = g.id
          WHERE f.id = ?";
$stmt = $GLOBALS['mysqli']->prepare($query);
if (!$stmt) {
  trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
}
else {
  $stmt->bind_param('i', $fietsId);
  if (!$stmt->execute()) {
    trigger_error($stmt->error, E_USER_ERROR);
  }
  $stmt->bind_result($fietsId, $borg, $prijs, $eigenaarId, $foto, $eigenaarNaam);
  // Check of de database de fiets kan vinden anders ga terug naar de hoofdpagina
  if (!$stmt->fetch()) {
    RedirectToPage("index.php");
  }
  $stmt->close();
  // De eigenaar kan niet zijn eigen fiets reserveren dus ga terug naar hoofdpagina als dit het geval is
  if ($eigenaarId == $_SESSION['id']) {
    RedirectToPage("fiets.php?fiets_id=$fietsId");
  }
}

// Check of de gebruiker niet stiekem <script> gebruikt
$collectionDate = $GLOBALS['mysqli']->real_escape_string($_GET['collectionDate']);
$returnDate = $GLOBALS['mysqli']->real_escape_string($_GET['returnDate']);
// Bereken de huurprijs:
$earlier = new DateTime($collectionDate);
$later = new DateTime($returnDate);
$amountOfDays = ($later->diff($earlier)->format("%a") + 1);
$totalePrijs = $amountOfDays * $prijs;
// Het aantal gehuurde dagen mag nooit lager zijn dan 0 en je mag ook niet huren als die dag al is geweest of als de returnDate lager is dan de collectionDate
$dateNow = new DateTime();
if ($earlier > $later) {
  RedirectToPage("fiets.php?fiets_id={$_GET['fiets_id']}");
}

?>
<div class="wrapper">
  <form method="post">
    Reservatie van <?php echo $collectionDate ?> tot en met <?php echo $returnDate ?>.<br>
    <!-- TODO: Fix dat je geen lagere terugbrengtijd kan kiezen dan de ophaaltijd -->
    <?php
      $timeStamps = "";
      for ($i=0; $i < 24; $i++) {
        $h = "$i";
        if ($i < 10) { $h = "0$h"; }
        for ($j=0; $j < 4; $j++) {
          $m = ($j*15);
          if ($m<10) { $m = "0$m"; }
          $timeStamps .= "<option value='$h:$m'>$h:$m</option>";
        }
      }
    ?>
    Ophaaltijd
    <select name="collectionTime">
      <?php echo $timeStamps; ?>
    </select>
    Terugbrengtijd
    <select name="returnTime">
      <?php echo $timeStamps; ?>
    </select><br>
    Totale huurprijs: €<?php echo $totalePrijs ?><br>
    Borg: <?php echo $borg ?><br>
    Eventueele bericht aan de eigenaar(<?php echo $eigenaarNaam ?>) van de fiets:<br>
    <textarea name="message" style="width:300px;height:200px;resize:none"></textarea><br>
    <input type="submit" name="verstuur" value="Verstuur verzoek voor het lenen van fiets">
  </form>
  <a href='fiets.php?fiets_id=$fietsId'>Klik op deze link om terug te gaan</a><br>

<?php

if (isset($_POST['verstuur'])) {
  // Gebruik real escape string voor het geval dat er <sript> word gebruikt in een van de gegevens inputs
  $collectionTime = $GLOBALS['mysqli']->real_escape_string($_POST['collectionTime']);
  $returnTime = $GLOBALS['mysqli']->real_escape_string($_POST['returnTime']);
  if ($collectionTime == "" || $returnTime == "") {
    return;
  }
  $collectionMomement = ($collectionDate . " " . $collectionTime . ":00");
  $returnMomement = ($returnDate . " " . $returnTime . ":00");
  $message = "";
  if (isset($_POST['message']) && $_POST['message'] != "") {
    $message = $GLOBALS['mysqli']->real_escape_string($_POST['message']);
  }
  // Voeg leenverzoek toe
  // Meer informatie over Dual: https://www.w3resource.com/sql/sql-dual-table.php
  // In de query word gecheckt of die datum en tijd beschikbaar is of de tijd niet in het verleden is en of de gebruiker voldoende geld heeft
  $query = "INSERT INTO leen_verzoek (fiets_id, lener_id, ophaal_moment, terugbreng_moment, bericht, prijs, borg)
            SELECT ?, ?, ?, ?, ?, ?, ?
            FROM Dual
            WHERE NOT EXISTS (
              SELECT v.fiets_id, v.ophaal_moment, v.terugbreng_moment, v.status_
              FROM leen_verzoek v
              WHERE (
                  ? = v.fiets_id AND (v.status_ = 'in_gebruik' OR v.status_ = 'gereserveerd')
                  AND (
                    ((CAST(? AS datetime)) >= v.ophaal_moment AND (CAST(? AS datetime)) <= v.terugbreng_moment)
                    OR ((CAST(? AS datetime)) >= v.ophaal_moment AND (CAST(? AS datetime)) <= v.terugbreng_moment)
                  )
                )
                OR (
                  ((CAST(? AS datetime)) <= NOW()
                )
                OR (
                  (
                    SELECT COALESCE(SUM((DATEDIFF(terugbreng_moment, ophaal_moment)+1)*prijs), 0) + ? + ? + (
                      SELECT COALESCE(sum(b.borg), 0)
                      FROM (
                          SELECT borg
                          FROM leen_verzoek
                          WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')
                          GROUP BY fiets_id, lener_id, borg
                      ) AS b
                    )
                    FROM leen_verzoek
                    WHERE lener_id = ? AND (status_ = 'in_afwachting' OR status_ = 'in_gebruik' OR status_ = 'gereserveerd')
                  )
                  >=
                  (
                    SELECT geld
                    FROM gebruiker
                    WHERE id = ?
                  )
                )
              )
            ) LIMIT 1";

  $stmt = $GLOBALS['mysqli']->prepare($query);
  if (!$stmt) {
    trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
  }
  else {
    $stmt->bind_param('iisssddisssssddiii', $fietsId, $_SESSION['id'], $collectionMomement, $returnMomement, $message, $prijs, $borg, $fietsId, $collectionMomement, $collectionMomement, $returnMomement, $returnMomement, $collectionMomement, $borg, $totalePrijs, $_SESSION['id'], $_SESSION['id'], $_SESSION['id']);
    if (!$stmt->execute() || $GLOBALS['mysqli']->affected_rows == 0) {
      echo "Helaas kon het vezoek tot lenen niet worden voltooid wellicht was er net iemand voor u die de fiets heeft gereserveerd of heeft u niet voldoende geld op uw account staan, <a href='geld.php'>klik dan op deze link.</a>";
      return;
    }
    $stmt->close();
    echo "U heeft met succes een leen verzoek gestuurd, <a href='leen_verzoeken.php'>bekijk hier al je leenverzoeken.</a>";
  }

}
?>

</div>
