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

    require_once('plugins/phpqrcode/qrlib.php');
    require_once('utils/database_connection.php');
    require_once('utils/core_functions.php');

    /// @brief Deze functie genereert een QRcode voor een fiets met daarin een link. In de link staat informatie over het fiets ID en de token van de fiets.
    /// @param $fietsId De id van de fiets waar je een QRcode voor wilt maken.
    /// @return void
    function GenerateQR($fietsId) {
      $fileName = ("fietsen/{$fietsId}/qr.svg");
      // 1. Check of er al een qr code voor deze fiets bestaat
      if (!file_exists($fileName)) {
        // Maak een folder aan als die nog niet bestaat voor deze fiets
        if (!is_dir("fietsen/{$fietsId}")) {
          mkdir("fietsen/{$fietsId}");
        }
        // 2. Maak een token
        $token = GetToken();
        // 3. Insert de token in de database
        $query = "UPDATE fietsen
                  SET token = ?
                  WHERE id = ?";
        $stmt = $GLOBALS['mysqli']->prepare($query);
        if (!$stmt) {
          trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
        }
        else {
          $stmt->bind_param('si', $token, $fietsId);
          if (!$stmt->execute()) {
            trigger_error($stmt->error, E_USER_ERROR);
          }
          $stmt->close();
          // 4. Maak de qr.svg bestand
          $QRdescription = ("inloggen.php?qr=true&fietsId={$fietsId}&token={$token}");
          QRcode::svg($QRdescription, $fileName);
        }
      }
    }

    /// @brief Delete de QR code die bij die fiets hoort
    /// @param $fietsId De id van de fiets
    /// @return void
    function DeleteQR($fietsId) {
      unlink("fietsen/{$fietsId}/qr.svg");
    }

?>
