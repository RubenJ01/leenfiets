<?php
/**
 * @file dashboard.php
 *
 * @brief Op deze pagina kunnen admins statistieken en informatie over de website zien.
 *
 * Admins kunnen hier klachten en andere informatie vinden.
 */

include 'utils/core_functions.php';
include 'utils/database_connection.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['rol'])) {
    // checkt of de gebruiker een admin is.\
    if ($_SESSION['rol'] != 'admin') {
        // functie uit core_functions.php als vervanger voor header()
        RedirectToPage('index.php');
    } else {
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script
          src="https://code.jquery.com/jquery-3.3.1.min.js"
          integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
          crossorigin="anonymous">
    </script>
</head>
<body>
    <script>
        // dit zorgt ervoor dat de gebruikertabel paginas kan hebben
        $(document).ready(function(){
            $('#data').after('<div id="nav"></div>');
            var rowsShown = 8;
            var rowsTotal = $('#data tbody tr').length;
            var numPages = rowsTotal/rowsShown;
            for(i = 0;i < numPages;i++) {
                var pageNum = i + 1;
                $('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
            }
            $('#data tbody tr').hide();
            $('#data tbody tr').slice(0, rowsShown).show();
            $('#nav a:first').addClass('active');
            $('#nav a').bind('click', function(){
            $('#nav a').removeClass('active');
            $(this).addClass('active');
            var currPage = $(this).attr('rel');
            var startItem = currPage * rowsShown;
            var endItem = startItem + rowsShown;
            $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
            css('display','table-row').animate({opacity:1}, 300);
            });
        // dit zorgt ervoor dat de fiets tabel paginas kan hebben
        });
        $(document).ready(function(){
            $('#fietsen').after('<div id="nav2"></div>');
            var rowsShown = 8;
            var rowsTotal = $('#fietsen tbody tr').length;
            var numPages = rowsTotal/rowsShown;
            for(i = 0;i < numPages;i++) {
                var pageNum = i + 1;
                $('#nav2').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
            }
            $('#fietsen tbody tr').hide();
            $('#fietsen tbody tr').slice(0, rowsShown).show();
            $('#nav2 a:first').addClass('active');
            $('#nav2 a').bind('click', function(){
            $('#nav2 a').removeClass('active');
            $(this).addClass('active');
            var currPage = $(this).attr('rel');
            var startItem = currPage * rowsShown;
            var endItem = startItem + rowsShown;
            $('#fietsen tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
            css('display','table-row').animate({opacity:1}, 300);
            });
        });
    </script>
    <div><?php include 'menu.php'; ?></div>
    <button onclick="toggle_visibility('user_div')">Gebruikers</button>
    <button onclick="toggle_visibility('merk_div')">Merk</button>
    <button onclick="toggle_visibility('fiets_div')">Fietsen</button>
    <div id="user_div" style="display: none;">
        <table id="data">
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Id</th>
                <th>Geverifieerd</th>
                <th>Rol</th>
                <th>Geven geven</th>
                <th>Admin verwijderen</th>
                <th>Gebruiker verwijderen</th>
            </tr>
            <?php
            $sql_userinfo = "select id, naam, email, rol, status_code from gebruiker";
            $resultaat_userinfo = $mysqli->query($sql_userinfo);
            while ($row = $resultaat_userinfo->fetch_assoc()) {
                echo "
                <tr>
                  <td>" . htmlentities($row['naam']) . "</td>
                  <td>" . htmlentities($row['email']) . "</td>
                  <td>" . htmlentities($row['id']) . "</td>
                  <td>" . htmlentities($row['status_code']) . "</td>
                  <td>" . htmlentities($row['rol']) . "</td>
                  <td>
                      <form action='' method='post'>
                        <input type='text' name='gebruiker_email' value='{$row['email']}' style='display: none;'>
                        <input type='text' name='actie' value='admin_geven' style='display: none;'>
                        <input type='submit' name='admin_aanpassing_versturen' value='geven'>
                      </form>
                  </td>
                  <td>
                      <form action='' method='post'>
                        <input type='text' name='gebruiker_email' value='{$row['email']}' style='display: none;'>
                        <input type='text' name='actie' value='admin_verwijderen' style='display: none;'>
                        <input type='submit' name='admin_aanpassing_versturen' value='verwijderen'>
                      </form>
                  </td>
                  <td>
                      <form action='' method='post'>
                        <input type='text' name='gebruiker_email' value='{$row['email']}' style='display: none;'>
                        <input type='submit' name='gebruiker_aanpassing_versturen' value='verwijderen'>
                      </form>
                  </td>
                </tr>
                ";
            }
            ?>
        </table>
    </div>
    <?php
    $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
    $result = $mysqli->query($sql);
    ?>
    <div id="merk_div" style="display: none;">
    <table>
            <tr>
                <th>Merk</th>
                <th>Id</th>
                <th>Verwijderen<th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()){
                echo "
                <tr>
                    <td>" . htmlentities($row['merk_naam']) . "</td>
                    <td>" . htmlentities($row['id']) . "</td>
                    <td> 
                        <form method='post' action=''>
                            <input type='submit' name='merk_aanpassing_versturen' value='Verwijderen'>
                            <input type='text' name='merk_naam' value='{$row['merk_naam']}' style='display: none;'>
                            <input type='text' name='merk_aanpassen' value='verwijderen' style='display: none;'>
                        </form>
                    </td>
                </tr>
                ";
            }
            ?>
        </table>
        <form method="post" action="" id="merk_aanpassen">
            <h3>Merk Toevoegen</h3>
            <label for="nieuw_merk">Merk</label>
            <input type="text" name="merk_naam" id="merk_naam" placeholder="Merk naam"> <br />
            <input type='text' name='merk_aanpassen' value='toevoegen' style='display: none;'>
            <input type="submit" name="merk_aanpassing_versturen" value="Uitvoeren">
        </form>
    </div>
    <div id="fiets_div" style="display: none;">
    <?php
        $sql = "SELECT g.email email, f.id id, f.borg borg, f.model model, f.status status
        FROM gebruiker g
        JOIN fietsen f ON f.gebruiker_id = g.id";
        $result = $mysqli->query($sql);
    ?>   
    <table id="fietsen">
        <tr>
            <th>E-mail</th>
            <th>Id</th>
            <th>Borg</th>
            <th>Model</th>
            <th>Status</th>
            <th>Verwijderen</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()){
            echo "
            <tr>
                <td>" . htmlentities($row['email']) . "</td>
                <td>" . htmlentities($row['id']) . "</td>
                <td>" . htmlentities($row['borg']) . "</td>
                <td>" . htmlentities($row['model']) . "</td>
                <td>" . htmlentities($row['status']) . "</td>
                <td> <form method='post' action=''> 
                        <input type='submit' name='fiets_verwijdering_versturen' value='verwijderen'>
                        <input type='text' name='fiets_id' value='{$row['id']}' style='display: none;'>
                    </form>
                </td>
            </tr>
            ";
            }
        ?>     
    </table>
    </div>
    <script src="js/dashboard.js"></script>
    <?php
    if (isset($_POST['merk_aanpassing_versturen'])) {
        $merk_naam = $_POST['merk_naam'];
        $aanpassing = $_POST['merk_aanpassen'];
        if ($aanpassing == 'toevoegen'){
            $check_bestaan = "select merk_naam from merk_fiets where merk_naam = '$merk_naam'";
            $result_check = $mysqli->query($check_bestaan);
            if ($result_check->num_rows == 0){
                $voeg_toe = "insert into merk_fiets(merk_naam)
                             values('$merk_naam')";
                $result = $mysqli->query($voeg_toe);
               echo createAlert("$merk_naam toegevoegd aan de database.");
            } else {
                echo createAlert("Dat merk bestaat al.");
            }
        } elseif ($aanpassing == 'verwijderen'){
            $check_bestaan = "select merk_naam from merk_fiets where merk_naam = '$merk_naam'";
            $result = $mysqli->query($check_bestaan);
            if ($result->num_rows == 1){
                $verwijder = "delete from merk_fiets where merk_naam = '$merk_naam' limit 1";
                $result = $mysqli->query($verwijder);
                echo createAlert("$merk_naam verwijdert uit de database.");
            } else {
                echo createAlert("Dat merk bestaat niet.");
            }
        }
    }
    if (isset($_POST['admin_aanpassing_versturen'])) {
        $email = $_POST['gebruiker_email'];
        $actie = $_POST['actie'];
        $zoek_gebruiker = "select rol from gebruiker where email= '$email'";
        $result = $mysqli->query($zoek_gebruiker);
        if ($result->num_rows == 1){
            if ($actie == 'admin_geven'){
                $sql_update = "update gebruiker set rol = 'admin' where email = '$email'";
                $result = $mysqli->query($sql_update);
                echo createAlert("$email heeft nu admin rechten.");
            }
            elseif ($actie == 'admin_verwijderen') {
                $sql_update = "update gebruiker set rol = 'standaard' where email = '$email'";
                $result = $mysqli->query($sql_update);
                echo createAlert("$email heeft nu geen admin rechten meer.");
            }
        }
    }
    if (isset($_POST['gebruiker_aanpassing_versturen'])) {
        $email = $_POST['gebruiker_email'];
        $sql = "select email from gebruiker where email = '$email'";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 1) {
            $sql = "delete from gebruiker where email = '$email' limit 1";
            $result = $mysqli->query($sql);
            echo createAlert("Gebruiker met $email is verwijdert uit de database.");
        } else {
            echo createAlert("Gebruiker met $email is niet gevonden.");
        }
    }
    if (isset($_POST['fiets_verwijdering_versturen'])) {
        $fiets_id = $_POST['fiets_id'];
        $sql = "SELECT id FROM fietsen WHERE id = '$fiets_id'";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 1){
            $sql = "DELETE FROM fietsen WHERE id = '$fiets_id'";
            $result = $mysqli->query($sql);
            if (!$result){
                echo $mysqli->error;
            }
            echo createAlert("Fiets met id $fiets_id is verwijdert uit de database.");
        } else {
            echo createAlert("Fiets met id $fiets_id is niet gevonden.");
        }
    }
    ?>
</body>
</html>
<?php
    }
} else {
    RedirectToPage('index.php');
}
?>
