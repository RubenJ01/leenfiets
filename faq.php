<?php
/**
* @file faq.php
*
* @brief Op deze pagina kun je onze faq vinden.
*
* De faq bestaat uit veel gevraagde vragen over onze website.
*/
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Registreren</title>
    <meta charset="UTF-8">
</head>
<body>
<div><?php include 'menu.php'; ?></div>
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
        <script src="js/faq.js"></script>
    </div>
</body>
</html>

