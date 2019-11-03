<?php

    /**
     * @file qrcode.php
     *
     * @brief qrcode script.
     *
     * Dit script is bedoelt om qr codes te generen die een link bevat naar de login pagina. Als de gebruiker inlogt wordt de fiets aan hem geleent of teruggebracht naar de eigenaar.
    */

    require_once('plugins/phpqrcode/qrlib.php');
    require_once('utils/database_connection.php');

    /// @brief Deze functie genereert een QRcode voor een leen_verzoek met daarin een link. In de link staat informatie over het leen_verzoek ID en de token van de fiets.
    /// @param $leen_verzoekId De id van het leen_verzoek waar je een QRcode voor wilt maken.
    /// @param $token Een gegeneerde token door de functie GetToken()
    /// @return void
    function GenerateQR($leen_verzoekId, $token) {
      $fileName = ("qr/$leen_verzoekId"."_"."$token.svg");
      // 1. Check of er al een qr code voor deze fiets bestaat en als dat niet zo is dan maken we er een
      if (!file_exists($fileName)) {
        // 3. Insert de token in de database
        $query = "UPDATE leen_verzoek
                  SET token = ?
                  WHERE id = ?";
        $stmt = $GLOBALS['mysqli']->prepare($query);
        if (!$stmt) {
          trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
        }
        else {
          $stmt->bind_param('si', $token, $leen_verzoekId);
          if (!$stmt->execute()) {
            trigger_error($stmt->error, E_USER_ERROR);
          }
          $stmt->close();
          // 4. Maak de qr.svg bestand
          //$QRdescription = ("192.168.2.68/ict-project/inloggen.php?qr=true&leen_verzoek={$leen_verzoekId}&token={$token}");
          $QRdescription = ("http://leenfiets.antonbonder.nl/inloggen.php?qr=true&leen_verzoek={$leen_verzoekId}&token={$token}");
          QRcode::svg($QRdescription, $fileName);
        }
      }
    }

    /// @brief Delete de QR code die bij die fiets hoort
    /// @param $leen_verzoekId De id van het leenverzoek
    /// @param $token de token die bij het leen verzoek hoort
    /// @return void
    function DeleteQR($leen_verzoekId, $token) {
      unlink("qr/$leen_verzoekId"."_"."$token.svg");
    }

    // Goeie links voor meer informatie over random_bytes.
    // Deze functie is platform onafhankelijk en openssl_random_pseudo_bytes is dat niet
    // https://stackoverflow.com/questions/38716613/generate-a-single-use-token-in-php-random-bytes-or-openssl-random-pseudo-bytes
    // https://www.php.net/manual/en/function.random-bytes.php
    /// @brief Deze functie geeft een token in hexadecimals. Hou ermee rekening dat de length van de string die je terugkrijgt het dubbele is van de opgegeven length
    /// @param $length length is optioneel, de standaard waarde is 8
    /// @return string
    function GetToken($length = 8) {
      if ($length < 8) {
        $length = 8;
      }
      return bin2hex(random_bytes($length));
    }

?>
