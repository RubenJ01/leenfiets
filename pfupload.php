<?php

if (isset($_POST['upload']))    {
    $file = $_FILES['file'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0)   {
            if ($fileSize < 1000000) {
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'uploads/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
               // header("Location: profiel.php?uploadsuccess");
            } else {
                echo "Uw afbeelding is te groot.";
            }
        } else {
            echo "Er was een error tewijl we uw afbeelding probeerden te uploaden.";
        }
    }   else {
        echo "Dit type afb is niet geschikt.";
    }
}
?>


