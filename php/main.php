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
            $privateKey = $keys[1]; # Sets the variable privateKey to the 1st item in the array
            $publicKey = $keys[0]; # Sets the variable publicKey to the 2nd item in the array
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
    
    if ( password_verify($password, $dataOfQuery["password"]) ) { # Compares the password that the user entered with the one stored in the table
        echo "the username and password you entered were a valid combination"; # if it was successful tell the user they entered a correct username/password combo

        $userID = $dataOfQuery["userID"]; # Saves the variables returned from query into these temporary variables
        $publicKey = $dataOfQuery["publicKey"];
        $privateKey = $dataOfQuery["privateKey"];

        $currentUser = new user($username, $userID, $publicKey, $privateKey); # Creates a new user class object using the data fetched from the query

        $_SESSION["currentUser"] = $currentUser; # Stores the new user object as a session variable
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

function determineUserIDSize($size, $userID_1, $userID_2) {
    if ( $size == TRUE ) { # If size input is TRUE then return largest userID
        if ( $userID_1 < $userID_2 ) { # If userID_1 is less than userID_2 then
            return $userID_2; # Return userID_2 (the bigger value)
        } else { # If userID_2 is less than userID_1 then
            return $userID_1; # Return userID_1 (the bigger value)
        }
    } else { # If size input is FALSE then return smallest userID
        if ( $userID_1 < $userID_2 ) { # If userID_1 is less than userID_2 then
            return $userID_1; # Return userID_1 (the smaller value)
        } else { # If userID_2 is less than userID_1 then
            return $userID_2; # Return userID_2 (the smaller value)
        }
    }
}

class user {
    public $username;
    public $userID;
    public $publicKey;
    public $contacts;

    private $privateKey;

    function __construct($username, $userID, $publicKey, $privateKey) { # Creation of new entity takes these 4 parameters
        $this->username = $username; # Assigns the objects variables to the ones entered as parameters
        $this->userID = $userID;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $SQL = "SELECT CASE WHEN userIDlow='$this->userID' THEN userIDhigh WHEN userIDhigh='$this->userID' THEN userIDlow END AS contact FROM contacts;";
        # SQL statement that fetches the userID of any contacts belonging to the user with the current entities userID from the contacts table
        $conn = databaseConnect(); # Set $conn to the result of the databaseConnect() function
        $resultOfQuery = mysqli_query($conn, $SQL); # Query the database with the previous SQL query
        $this->contacts = array(); # Set the object's contacts variable to a blank array
        while ( $dataOfQuery = mysqli_fetch_assoc($resultOfQuery) ) { # While loop that goes through every returned row
            if ( $dataOfQuery["contact"] != NULL ) { # Check if the given result is NULL
                array_push($this->contacts, $dataOfQuery["contact"]); # If not NULL then push this result to the contacts array
            }
        }
    }

    function updatePassword($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $SQL = "UPDATE users SET `password`='$hashedPassword' WHERE `userID`='$this->userID';";
        $conn = databaseConnect();
        $resultOfQuery = mysqli_query($conn, $SQL);
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
        
        $lowestUserID = determineUserIDSize(FALSE, $this->userID, $contactUserID);
        $highestUserID = determineUserIDSize(TRUE, $this->userID, $contactUserID);

        if ( $numberOfRows != 0 ) {
            array_push($this->contacts, $contactUserID);
            $SQL = "INSERT INTO `contacts` (`userIDlow`, `userIDhigh`) VALUES ('$lowestUserID', '$highestUserID');";
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

        $lowestUserID = determineUserIDSize(FALSE, $this->userID, $currentUser->userID);
        $highestUserID = determineUserIDSize(TRUE, $this->userID, $currentUser->userID);
        
        $SQL = "INSERT INTO `messages` (`userIDlow`, `userIDhigh`, `senderID`, `message`) VALUES ('$lowestUserID', '$highestUserID', '$currentUser->userID', '$encryptedMessage');";
        $conn = databaseConnect();
        $resultOfQuery = mysqli_query($conn, $SQL);
    }  // NEED better method to encrypt messages because current method sucks balls, essentially only useful if attacker has stolen entire messages table. and nothing else.

    function renderMessages() {
        $currentUser = $_SESSION["currentUser"];
        $userIDlow = determineUserIDSize(FALSE, $this->userID, $currentUser->userID);
        $userIDhigh = determineUserIDSize(TRUE, $this->userID, $currentUser->userID);
        $conn = databaseConnect();

        $SQL = "SELECT `userIDlow`, `userIDhigh`, `senderID`, `message` FROM `messages` WHERE `userIDlow`='$userIDlow' AND `userIDhigh`='$userIDhigh';";
        
        $resultOfQuery = mysqli_query($conn, $SQL);
        while ( $record = mysqli_fetch_assoc($resultOfQuery) ) {
            $message = $record["message"];
            $message = hex2bin($message);

            switch ( $record["senderID"] ) {
                case $this->userID: # If the sender was the contact then
                    openssl_private_decrypt($message, $decryptedMessage, $currentUser->getPrivateKey());
                    echo "1";
                    echo $decryptedMessage;
                    echo "2";
                    $HTML = '
                    <div id="stretch">
                        <div id="left" class="message">
                        ' . $decryptedMessage . '
                        </div>
                    </div>
                    ';
                    break;
                case $currentUser->userID: # If the sender was the user then
                    openssl_private_decrypt($message, $decryptedMessage, $this->getPrivateKey());
                    $HTML = '
                    <div id="stretch">
                        <div id="right" class="message">
                        ' . $decryptedMessage . '
                        </div>
                    </div>
                    ';
                    break;
            }
            echo $HTML;
        }
    }
}
?>