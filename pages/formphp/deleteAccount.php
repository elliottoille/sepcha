<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $password = prepUserInput($_POST["password"]);
    if ( isset($_SESSION["currentUser"]) ) {
        $_SESSION["currentUser"]->deleteUser($password);
    } else {
        echo "you aren't logged in";
    }
}
header('Location: ../settings/categories/account.php');
?>