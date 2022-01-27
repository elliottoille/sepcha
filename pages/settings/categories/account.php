<?php
include '../../../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<html>
    <body>
        <form action="../../formphp/updatePassword.php" method="POST">
            <label for="password">Update Password:</label>
            <input id="password" type="password" placeholder="password" name="password" required>

            <input id="confirmPassword" type="password" placeholder="confirm password" name="confirmPassword" onkeyup="checkPasswordMatch();" required>

            <button id="btn" type="submit">sign up</button>

            <p id="passwordMatchAlert"></p>
        </form>
        <script src="../../../js/main.js" type="text/javascript"></script>
    </body>
</html>