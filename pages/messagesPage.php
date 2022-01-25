<?php
include '../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<link rel="stylesheet" href="../styles/messagesPage.css">
<body>
<iframe id="contactsFrame" src="messages/contactsPage.php" frameborder="0"></iframe>
<iframe id="messagesFrame" src="messages/messagesPage.php" frameborder="0" name="messagesFrame"></iframe>
</body>