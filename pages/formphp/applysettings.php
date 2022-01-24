<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $font = prepUserInput($_POST["font"]);
    if ( isset($currentContact) ) {
        $currentContact->updateFont($font);
    } else {
        echo "you have not selected a contact";
    }
}
header('Location: ../settings/categories/customisation.html');
?>