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
            
            <label>Background:</label>
            <input id="background" type="color" name="background" <?php echo "value='" . $user->settings["background"] . "'";?>>
            <br>
            <label>Hover:</label>
            <input id="hover" type="color" name="hover" <?php echo "value='" . $user->settings["hover"] . "'";?>>
            <br>
            <label>Secondary:</label>
            <input id="secondary" type="color" name="secondary" <?php echo "value='" . $user->settings["secondary"] . "'";?>>
            <br>
            <label>Text:</label>
            <input id="text" type="color" name="text" <?php echo "value='" . $user->settings["text"] . "'";?>>

            <input type="submit" value="Apply Changes">
        </form>
    </body>
</html>