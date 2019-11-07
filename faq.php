<?php
/**
* @file faq.php
*
* @brief Op deze pagina kun je onze faq vinden.
*
* De faq bestaat uit veel gevraagde vragen over onze website.
*/
include 'menu.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Registreren</title>
    <meta charset="UTF-8">
</head>
<body>
<div class="page">
    <div class="page-part">
        <h1>FAQ</h1>
        <a onclick="hideOrShow(0)">> Ik krijg geen E-mail binnen na het registreren.</a>
        <div class="Answer">
            <p>Dit betekent dat je E-mail adres verkeerd is. Mocht dit niet het geval zijn probeer het opnieuw.</p>
        </div>
        <a onclick="hideOrShow(1)">> Door wie is deze site gemaakt?</a>
        <div class="Answer">
            <p>Deze site is gemaakt en ontworpen door 5 eerste jaars studenten op de opleiding HBO Ict.</p>
        </div>
        <a onclick="hideOrShow(2)">> Hoe werkt het lenen/uitlenen?</a>
        <div class="Answer">
            <p>Hoe werkt het?<br>
            Stap1: Laat de qr code scannen door de lener vervolgens wordt het geld overgemaakt naar uw account ook kunt u de code sturen naar de lener deze kan hij vervolgens zelf invoeren om de fiets te lenen. <br>
            Stap2: Wanneer de lener de fiets heeft teruggebracht laat hem opnieuw een qr code scannen (of laat de lener weer zelf de code invullen) zodat de fiets weer gemarkeerd staat als teruggebracht in het systeem. De lener krijgt dan vervolgens zijn borg terug.<br>
            <br>
            Neem <a href="contact.php">contact</a> met ons op in de volgende gevallen:<br>
            - Mocht er schade zijn gemaakt aan de fiets, dan kijken wij hoe erg de schade is en krijg je een gedeelte van de borg die de lener bij ons heeft staan.<br>
            - Mocht de fiets gestolen zijn neem contact met ons op zodat wij dit kunnen uitzoeken. En als die daadwerkelijk gestolen is krijgt u de volledige borg van ons.<br></p>
        </div>
        <script src="js/faq.js"></script>
    </div>
</body>
</html>
