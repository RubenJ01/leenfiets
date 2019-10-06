<?php

    /**
     * @file qrcode.php
     *
     * @brief qrcode script.
     *
     * Dit script is bedoelt om qr codes te generen die een link bevat naar de login pagina. Als de gebruiker inlogt wordt de fiets aan hem geleent of teruggebracht naar de eigenaar.
    */

    // Goeie links voor meer informatie over random_bytes.
    // Deze functie is platform onafhankelijk en openssl_random_pseudo_bytes is dat niet
    // https://stackoverflow.com/questions/38716613/generate-a-single-use-token-in-php-random-bytes-or-openssl-random-pseudo-bytes
    // https://www.php.net/manual/en/function.random-bytes.php

    // TODO: Delete QR naar dat die gescant is

    require_once('..\plugins\phpqrcode\qrlib.php');

    GenerateQR(1);

    /// @brief Deze functie genereert een QRcode voor een fiets met daarin een link. In de link staat informatie over het fiets ID en de token van de fiets.
    /// @param $fietsId De id van de fiets waar je een QRcode voor wilt maken.
    /// @return void
    function GenerateQR($fietsId) {
      $fileName = ($fietsId.'.svg');
      // Genereer svg bestand met een token voor de fiets
      if (!file_exists($fileName)) {
          $token = GetToken();
          // TODO: Sla de token op in de database

          $QRdescription = ("inloggen.php?qr=true&fietsId={$fietsId}&token={$token}");
          QRcode::svg($QRdescription, $fileName);
          echo "File generated! Token: {$token}";
      }

      if (file_exists($fileName)) {
        echo "<img src='{$fileName}' style='width:500;height:auto;'>";
      }
    }

    /// @brief Delete de QR code die bij die fiets hoort
    /// @param $fietsId De id van de fiets
    /// @return void
    function DeleteQR($fietsId) {
      unlink($fietsId.'.svg');
    }

    /// @brief Deze functie geeft een token met
    /// @param $length length is optioneel, de standaard waarde is 8
    /// @return string
    function GetToken($length = 8) {
      if ($length < 8) {
        $length = 8;
      }
      return bin2hex(random_bytes($length));
    }

?>
