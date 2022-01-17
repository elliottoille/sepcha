<?php
header("Content-type: text/css, charset: UTF-8");

$user = $_SESSION["currentUser"];
$font = $user->$settings["font"];
?>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap');
* {
    font-family: <?php echo $font; ?>, sans-serif;
    color: green;
}
