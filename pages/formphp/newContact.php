<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $contactUsername = prepUserInput($_POST["contactUsername"]);
    $currentUser = $_SESSION["currentUser"];
    $currentUser->newContact($contactUsername);
    header('Location: ../messages/contactsPage.php');
}
?>