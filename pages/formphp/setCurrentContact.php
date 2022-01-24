<?php
include '../../php/main.php'; # Include code from file main.php
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) { # If the files receives a POST request aimed at this file then
    $userID = $_POST["contact"]; # Set the variable $userID to the userID sent by the button press

    $SQL = "SELECT `username`, `publicKey`, `privateKey` FROM users WHERE `userID`='$userID';"; # SQL Query to fetch data of the given userID
    $conn = databaseConnect(); # Set $conn to the result of the databaseConnect() function
    $resultOfQuery = mysqli_query($conn, $SQL); # Query the database with the aforementioned SQL statement
    $dataOfQuery = mysqli_fetch_assoc($resultOfQuery); # Fetch the data that was returned by the previous SQL query

    $currentContact = new contact($dataOfQuery["username"], $userID, $dataOfQuery["publicKey"], $dataOfQuery["privateKey"]);
    # Set $currentContact to be a new contact object, that takes the variables fetched from the database and the userID that was passed via the button press as parameters
    $_SESSION["currentContact"] = $currentContact; # Create a session variable "currentContact" to the object that was just created
    header('Location: ../messages/contactsPage.php');
}
?>
