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
      <li> Ophaaldatum<br><input id="collectionDate" name="collectionDate" value="<?php $date = new DateTime(); echo $date->format('Y-m-d'); ?>" readonly> </li>
      <li> Terugbrengdatum<br><input id="returnDate" name="returnDate" value="<?php $date = new DateTime(); echo $date->format('Y-m-d'); ?>" readonly> </li>
    </ul>
    <ul class="reserve">
      <li><input type="submit" value="Doorgaan"></li>
    </ul>
  </form>

</div>
<script src="js/calendar.js"></script>
