<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $username = prepUserInput($_POST["username"]);
    $password = prepUserInput($_POST["password"]);
    userLogIn($username, $password);
}
?>