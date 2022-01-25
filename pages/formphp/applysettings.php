<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $font = prepUserInput($_POST["font"]);
    if ( isset($_SESSION["currentUser"]) ) {
        $_SESSION["currentUser"]->updateFont($font);
    } else {
        echo "you aren't logged in";
    }
}
header('Location: ../settings/categories/customisation.php');
?>