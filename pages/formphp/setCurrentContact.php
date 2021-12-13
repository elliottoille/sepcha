<?php
include '../../php/main.php';
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $userID = $_POST["contact"];

    $SQL = "SELECT `username`, `publicKey`, `privateKey` FROM users WHERE `userID`='$userID';";
    $conn = databaseConnect();
    $resultOfQuery = mysqli_query($conn, $SQL);
    $dataOfQuery = mysqli_fetch_assoc($resultOfQuery);

    $currentContact = new contact($dataOfQuery["username"], $userID, $dataOfQuery["publicKey"], $dataOfQuery["privateKey"]);   
    $_SESSION["currentContact"] = $currentContact;
}
?>
