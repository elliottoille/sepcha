<html>
    <head>
        <link rel="stylesheet" href="../../styles/font.php">
        <link rel="stylesheet" href="../../styles/messages.css">
    </head>
    <body>
        <?php
            include '../../php/main.php';

            if ( isset($_SESSION["currentContact"]) ) {
                $currentContact = $_SESSION["currentContact"];
                $currentContact->renderMessages();
            } else {
                echo "you have not selected a contact";
            }
        ?>
    </body>
</html>