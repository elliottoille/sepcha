<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $font = prepUserInput($_POST["font"]);
    $background = prepUserInput($_POST["background"]);
    $secondary = prepUserInput($_POST["secondary"]);
    $hover = prepUserInput($_POST["hover"]);
    $text = prepUserInput($_POST["text"]);
    if ( isset($_SESSION["currentUser"]) ) {
        $_SESSION["currentUser"]->updateSettings($font, $background, $secondary, $hover, $text);
    } else {
        echo "you aren't logged in";
    }
}
header('Location: ../settings/categories/customisation.php');
?>