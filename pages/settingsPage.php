<?php
include '../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<link rel="stylesheet" href="../styles/settingsPage.css">
<body>
<iframe id="categoriesFrame" src="settings/categoriesPage.php" frameborder="0"></iframe>
<iframe id="settingsFrame"  src="settings/categories/customisation.php" frameborder="0" name="settingsFrame"></iframe>
</body>