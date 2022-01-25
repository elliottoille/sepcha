<link rel="stylesheet" href="../../styles/font.css">
<link rel="stylesheet" href="../../styles/categoriesTab.css">
<?php
include '../../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<ul>
    <li><a href="categories/customisation.php" target="settingsFrame">Customisation</a></li>
</ul>