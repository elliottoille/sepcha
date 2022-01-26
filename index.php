<?php
include 'php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<html lang="en">
    <head>
        <title>SEPCHA</title>
        <link rel="stylesheet" href="styles/index.css">
        <meta charset="utf-8"/>
    </head>
    <body>
        <ul>
            <li><a href="pages/messagesPage.php" target="homeFrame">Messages</a></li>
            <li><a href="pages/loginPage.php" target="homeFrame">Log in</a></li>
            <li><a href="pages/signupPage.php" target="homeFrame">Sign up</a></li>
            <li><a href="pages/settingsPage.php" target="homeFrame">Settings</a></li>
            <li><a href="php/logout.php" target="homeFrame">Log out</a></li>
        </ul>
        <iframe name="homeFrame" src="pages/loginPage.php" frameborder="0"></iframe>
    </body>
</html>