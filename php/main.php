<?php
session_start();

function databaseConnect() {
    $servername = "localhost"; # Name of the server
    $username = "root"; # Username to access database
    $password = "xe5Y_U),xOrdw{Ku#iXD"; # Password of user 'root' to access database

    $database = "sepcha"; # The name of the database to be accessed on the server

    $conn = mysqli_connect($servername, $username, $password, $database); # Create a connection to the database using these variables
    if( !$conn ) { # If the connection fails then
        die("Error: ". mysqli_connect_error()); # Quit and report the error to the terminal
    }
    return $conn;
}

function compareStrings($str1, $str2) {
    if ( $str1 == $str2 ) { # If the two inputted strings match then
        return TRUE; # Return true
    } else { # Otherwise
        return FALSE; # Return false
    }
}

function prepUserInput($input) {
    $conn = databaseConnect();
    $input = mysqli_real_escape_string($conn, $input);
    return $input;
}

function userSignUp($username, $password, $confirmPassword) {
    $conn = databaseConnect();
    if ( compareStrings($password, $confirmPassword) ) {

        $SQL = "SELECT username FROM `users` WHERE `username`='$username';";
        $resultOfQuery = mysqli_query($conn, $SQL);
        $numberOfRows = mysqli_num_rows($resultOfQuery);

        if ( $numberOfRows == 0 ) {
            $keys = generateEncryptionKeys();
            $privateKey = $keys[0];
            $publicKey = $keys[1];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $SQL = "INSERT INTO `users` ( `username`, `password`, `privateKey`, `publicKey`) VALUES ('$username', '$hashedPassword', '$publicKey', '$privateKey');";
            $resultOfQuery = mysqli_query($conn, $SQL);
            userLogIn($username, $password);
        } else {
            echo "a user with that username already exists";
        }
    } else {
        echo "the passwords you entered did not match";
    }
}

function userLogIn($username, $password) {
    $conn = databaseConnect();
    
    $SQL = "SELECT * FROM `users` WHERE `username`='$username';";
    $resultOfQuery = mysqli_query($conn, $SQL);
    $dataOfQuery = mysqli_fetch_assoc($resultOfQuery);
    
    if ( password_verify($password, $dataOfQuery["password"]) ) {
        echo "the username and password you entered were a valid combination";

        $userID = $dataOfQuery["userID"];
        $publicKey = $dataOfQuery["publicKey"];
        $privateKey = $dataOfQuery["privateKey"];

        $currentUser = new user($username, $userID, $publicKey, $privateKey);

        $_SESSION["currentUser"] = $currentUser;
    }
}

function generateEncryptionKeys() {
    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 512,
        "private_key_type" => OPENSSL_KEYTYPE_RSA
    );
    $resultOfGen = openssl_pkey_new($config);
    openssl_pkey_export($resultOfGen, $privateKey);
    $publicKey = openssl_pkey_get_details($resultOfGen);
    $publicKey = $publicKey["key"];
    $keys = array (
        $privateKey,
        $publicKey
    );
    return $keys;
}

class user {
    public $username;
    public $userID;
    public $publicKey;
    public $contacts;

    private $privateKey;

    function __construct($username, $userID, $publicKey, $privateKey) {
        $this->username = $username;
        $this->userID = $userID;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->contacts = array();
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function getUserID() {
        return $this->userID;
    }

    function getUsername() {
        return $this->username;
    }

    function getPublicKey() {
        return $this->publicKey;
    }
    
    function getPrivateKey() {
        return $this->privateKey;
    }

    function newContact($contactUsername) {
        $conn = databaseConnect();
        $SQL = "SELECT `userID` FROM `users` WHERE `username`='$contactUsername';";
        $resultOfQuery = mysqli_query($conn, $SQL);
        $dataOfQuery = mysqli_fetch_assoc($resultOfQuery);
        $numberOfRows = mysqli_num_rows($resultOfQuery);
        $contactUserID = $dataOfQuery["userID"];
        
        if ( $numberOfRows != 0 ) {
            array_push($this->contacts, $contactUserID);
        } else {
            echo "the user that you tried to add does not exist";
            return;
        }
    }
}
?>