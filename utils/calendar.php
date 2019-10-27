<!-- Include this file into your page using include "utils/calendar.php" -->

<head>
  <link rel="stylesheet" type="text/css" href="css/calendar.css">
</head>

<div class="calendar">

  <div class="month">
   <ul>
     <li class="prev" onclick="previousMonth()">&#10094;</li>
     <li class="next" onclick="nextMonth()">&#10095;</li>
     <li id="month+year"><!-- Will be automaticlly filled by calendar.js --></li>
   </ul>
  </div>

  <ul class="weekdays">
   <li>Zo</li>
   <li>Ma</li>
   <li>Di</li>
   <li>Wo</li>
   <li>Do</li>
   <li>Vr</li>
   <li>Za</li>
  </ul>

  <ul class="days" id="days">
   <!-- Will be automaticlly filled by calendar.js -->
  </ul>
  <form action="reserveren.php" method="get">
    <input type="text" name="fiets_id" value="<?php echo $_GET['fiets_id'] ?>" style="display:none;">
    <ul class="dates">
      <li> Ophaaldatum<br><input id="collectionDate" name="collectionDate" value="Selecteer een datum" readonly> </li>
      <li> Terugbrengdatum<br><input id="returnDate" name="returnDate" value="Selecteer een datum" readonly> </li>
    </ul>
    <ul class="reserve">
      <li><input type="submit" value="Doorgaan"></li>
    </ul>
  </form>

  <!-- Laad hier alle gereserveerde datums -->
  <div id="reservedDates" style="display:none">
    <?php
      require_once "utils/database_connection.php";
      // Deze query haalt alle gereserveerde datums op van klein naar groot
      $query = "SELECT ophaal_moment, terugbreng_moment
                FROM leen_verzoek
                WHERE fiets_id = ? AND (status_ = 'in_gebruik' OR status_ = 'gereserveerd')
                ORDER BY ophaal_moment ASC";
      $stmt = $GLOBALS['mysqli']->prepare($query);
      if (!$stmt) {
        trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
      }
      else {
        $stmt->bind_param('i', $_GET['fiets_id']);
        if (!$stmt->execute()) {
          echo "ERROR in query";
          return;
        }
        // Echo all de opgehaalde rijen
        $stmt->bind_result($_ophaal_moment, $_terugbreng_moment);
        while ($stmt->fetch()) {
          echo "<div><div> $_ophaal_moment </div><div> $_terugbreng_moment </div></div>";
        }
        $stmt->close();
      }
    ?>
  </div>

</div>
<script src="js/calendar.js"></script>
