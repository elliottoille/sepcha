<?php
header("Content-type: text/css");

$user = $_SESSION["currentUser"];
$font = $user->$settings["font"];
?>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap');
* {
    font-family: '<?=$font?>', sans-serif;
    color: green;
}
