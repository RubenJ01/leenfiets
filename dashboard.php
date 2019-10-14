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
</head>
<body>
    <div><?php include 'menu.php'; ?></div>
    <button onclick="toggle_visibility('user_div')">Gebruikers</button>
    <button onclick="toggle_visibility('merk_div')">Merk</button>
    <div id="user_div" style="display: none;">
        <table id="user_tabel">
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Id</th>
                <th>Geverifieerd</th>
                <th>Rol</th>
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
                </tr>
                ";
            }
            ?>
        </table>
        <form method="post" action="" id="admin_geven">
            <h3>Admin rechten geven</h3>
            <label for="gebruiker_email">Gebruiker email</label>
            <input type="text" name="gebruiker_email" id="admin_geven"> <br />
            <label for="actie">Rechten toekennen</label>
            <input type="radio" name="actie" value="admin_geven"> <br />
            <label for="actie">Rechten verwijderen</label>
            <input type="radio" name="actie" value="admin_verwijderen"> <br>
            <input type="submit" name="admin_aanpassing_versturen" value="Uitvoeren">
        </form>
    </div>
    <?php
    $sql = "SELECT merk_naam, id FROM merk_fiets order by merk_naam asc";
    $result = $mysqli->query($sql);
    ?>
    <div id="merk_div" style="display: none;">
    <table id="merk_tabel">
            <tr>
                <th>Merk</th>
                <th>Id</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()){
                echo "
                <tr>
                    <td>" . htmlentities($row['merk_naam']) . "</td>
                    <td>" . htmlentities($row['id']) . "</td>
                </tr>
                ";
            }
            ?>
        </table>
        <form method="post" action="" id="merk_aanpassen">
            <h3>Merk Toevoegen</h3>
            <label for="nieuw_merk">Merk</label>
            <input type="text" name="merk_naam" id="merk_naam" placeholder="Merk naam"> <br />
            <label for="merk_toevoegen">Toevoegen</label>
            <input type="radio" name="merk_aanpassen" id="merk_toevoegen" value="toevoegen"> <br />
            <label for="merk_verwijderen">Verwijderen</label>
            <input type="radio" name="merk_aanpassen" id="merk_verwijderen" value="verwijderen"> <br />
            <input type="submit" name="merk_aanpassing_versturen" value="Uitvoeren">
        </form>
    </div>
    <script src="js/dashboard.js"></script>
</body>
</html>
<?php
    }
} else {
    RedirectToPage('index.php');
}

// controleren of we de goeie form behandelen
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
            echo $merk_naam . " toegevoegd aan de database.";
        } else {
            echo "Dat merk bestaat al.";
        }
    } elseif ($aanpassing == 'verwijderen'){
        $check_bestaan = "select merk_naam from merk_fiets where merk_naam = '$merk_naam'";
        $result = $mysqli->query($check_bestaan);
        if ($result->num_rows == 1){
            $verwijder = "delete from merk_fiets where merk_naam = '$merk_naam' limit 1";
            $result = $mysqli->query($verwijder);
            echo $merk_naam . " verwijdert uit de database.";
        } else {
            echo "Dat merk bestaat al.";
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
            echo $email .  " heeft nu admin rechten.";
        }
        elseif ($actie == 'admin_verwijderen') {
            $sql_update = "update gebruiker set rol = 'standaard' where email = '$email'";
            $result = $mysqli->query($sql_update);
            echo $email .  " heeft nu geen admin rechten meer.";
        }
    }
}
?>