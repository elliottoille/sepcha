<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $password = prepUserInput($_POST["password"]);
    $confirmPassword = prepUserInput($_POST["confirmPassword"]);

    if ( isset($_SESSION["currentUser"]) ) {
        $_SESSION["currentUser"]->updatePassword($password, $confirmPassword);
    } else {
        echo "you aren't logged in";
    }
}
header('Location: ../settings/categories/account.php');
?>