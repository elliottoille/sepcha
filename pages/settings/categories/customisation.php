<?php
include '../../../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<html>
    <body>
    <form action="../../formphp/applySettings.php" method="POST">
            <label>Font:</label>
            <input id="outfit" type="radio" name="font" value="Outfit" checked>
            <label for="outfit">Outfit</label>
            <input id="arial" type="radio" name="font" value="arial">
            <label for="arial">Arial</label>
            <input id="georgia" type="radio" name="font" value="Georgia">
            <label for="georgia">Georgia</label>
            <br>
            <label>Theme:</label>
            <input id="light" type="radio" name="theme" value="light" checked>
            <label for="light">Light</label>
            <input id="dark" type="radio" name="theme" value="dark">
            <label for="dark">Dark</label>
            <input id="dusk" type="radio" name="theme" value="dusk">
            <label for="dusk">Dusk</label>
            <input type="submit" value="Apply Changes">
        </form>
    </body>
</html>