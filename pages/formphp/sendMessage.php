<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
    $message = prepUserInput($_GET["message"]);
    $currentContact = $_SESSION["currentContact"];
    if ( isset($currentContact) ) {
        $currentContact->newMessage($message);
    } else {
        echo "you have not selected a contact";
    }
}
header('Location: ../messages/messagesPage.php');
?>