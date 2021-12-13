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
    return $conn; # The connection is returned when the function is called
}

function compareStrings($str1, $str2) { # Takes two strings as parameters
    if ( $str1 == $str2 ) { # If the two inputted strings match then
        return TRUE; # Return true
    } else { # Otherwise
        return FALSE; # Return false
    }
}

function prepUserInput($input) { # Takes user input as a parameter
    $conn = databaseConnect(); # Uses the databaseConnect() function to get the $conn variable
    $input = mysqli_real_escape_string($conn, $input); # Calls the built-in function to prepare the user input
    return $input; # Returns the user input
}

function userSignUp($username, $password, $confirmPassword) { # Takes a username and a password twice as input
    $conn = databaseConnect(); # Uses the databaseConnect() function to get the $conn variable
    if ( compareStrings($password, $confirmPassword) ) { # Uses the compareStrings() to check if the two entered passwords match each other

        $SQL = "SELECT username FROM `users` WHERE `username`='$username';"; # SQL query that selects the usernames from the table where the username is the one entered by the user
        $resultOfQuery = mysqli_query($conn, $SQL); # Query the database using the previous SQL query
        $numberOfRows = mysqli_num_rows($resultOfQuery); # The amount of rows that the previous SQL query returned

        if ( $numberOfRows == 0 ) { # If nothing was returned from the previous SQL query
            $keys = generateEncryptionKeys(); # Uses the generateEncryptionKeys() function to generate a key pair and stores that in an array
            $privateKey = $keys[0]; # Sets the variable privateKey to the 1st item in the array
            $publicKey = $keys[1]; # Sets the variable publicKey to the 2nd item in the array
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); # Hashes the password using the built-in password_hash() function

            $SQL = "INSERT INTO `users` ( `username`, `password`, `privateKey`, `publicKey`) VALUES ('$username', '$hashedPassword', '$publicKey', '$privateKey');";
            # SQL query that inserts the data for the user into the users table
            $resultOfQuery = mysqli_query($conn, $SQL); # Queries the database with the previous SQL query
            userLogIn($username, $password); # Calls the userLogIn() function with the entered username and password
        } else { # If the first query resulted in a piece of data being returned then
            echo "a user with that username already exists";
        }
    } else { # If the passwords that the user entered do not match then
        echo "the passwords you entered did not match";
    }
}

function userLogIn($username, $password) { # Takes a prepared username and password as parameters
    $conn = databaseConnect(); # Uses the databaseConnect() function to get the $conn variable
    
    $SQL = "SELECT * FROM `users` WHERE `username`='$username';"; # SQL query that selects all the variables for a user where the username is the one the user entered
    $resultOfQuery = mysqli_query($conn, $SQL); # Queries the database with the previous SQL query
    $dataOfQuery = mysqli_fetch_assoc($resultOfQuery); # Stores the data fetched by the query 
    
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
        $SQL = "SELECT CASE WHEN userIDa='$this->userID' THEN userIDb WHEN userIDb='$this->userID' THEN userIDa END AS contact FROM contacts;";
        $conn = databaseConnect();
        $resultOfQuery = mysqli_query($conn, $SQL);
        $this->contacts = array();
        while ( $dataOfQuery = mysqli_fetch_assoc($resultOfQuery) ) {
            if ( $dataOfQuery["contact"] != NULL ) {
                array_push($this->contacts, $dataOfQuery["contact"]);
            }
        }
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
            $SQL = "INSERT INTO `contacts` (`userIDa`, `userIDb`) VALUES ('$this->userID', '$contactUserID');";
            $resultOfQuery = mysqli_query($conn, $SQL);
        } else {
            echo "the user that you tried to add does not exist";
            return;
        }
    }

    function renderContacts() {
        $conn = databaseConnect();
        $contacts = $this->contacts;
        if ( !(count($contacts) == 0) ) {
            foreach ($this->contacts as $contact) { // THIS IS VERY SLOW, CAN DO IN ONE SQL QUERY DON'T EVEN NEED CONTACTS ARRAY I THINK
                $SQL = "SELECT username FROM users WHERE userID='$contact';";
                $resultOfQuery = mysqli_query($conn, $SQL);
                $dataOfQuery = mysqli_fetch_assoc($resultOfQuery);
                $contactUsername = $dataOfQuery["username"];
                $HTML = '<li id="contactLi">
                            <form action="../formphp/setCurrentContact.php" method="POST">
                                <button id="contactBtn" name="contact" value=' . $contact . ' type="submit">' . $contactUsername . ' </button>
                            </form>
                        </li>';
                echo $HTML;
            }
        } else {
            echo "you have no contacts added";
        }
    }
}

class contact {
    public $username;
    public $userID;
    public $publicKey;

    private $privateKey;

    function __construct($username, $userID, $publicKey, $privateKey) {
        $this->username = $username;
        $this->userID = $userID;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        header('Location: ../messages/contactsPage.php');
    }

    function getPrivateKey() {
        return $this->privateKey;
    }

    function newMessage($message) {
        $currentUser = $_SESSION["currentUser"];
        openssl_public_encrypt($message, $encryptedMessage, $currentUser->publicKey);
        $encryptedMessage = prepUserInput(bin2hex($encryptedMessage));
        $SQL = "INSERT INTO `messages` (`userIDa`, `userIDb`, `message`) VALUES ('$currentUser->userID', '$this->userID', '$encryptedMessage');";
        $conn = databaseConnect();
        $resultOfQuery = mysqli_query($conn, $SQL);
    } // PROBLEMS: in messages table, userIDa and userIDb need to be consistent with the contacts table or else the messages will not know what user sent it
        // NEED better method to encrypt messages because current method sucks balls, essentially only useful if attacker has stolen entire messages table. and nothing else.
}
?>