<?php
include '../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<link rel="stylesheet" href="../styles/form.css">
<form action="formphp/login.php" method="POST">
    <input type="text" placeholder="username" name="username">

    <input type="password" placeholder="password" name="password">

    <button type="submit">sign in</button>
</form>