<?php
include '../../php/main.php';
if (isset( $_SESSION["currentUser"] )) {
    $user = $_SESSION["currentUser"];
    $user->renderUserSettings();
}
?>
<link rel="stylesheet" href="../../styles/font.css">
<link rel="stylesheet" href="../../styles/messagesPageSub.css">
<iframe id="messagesFrame" src="renderPage.php" frameborder="0" name="renderFrame"></iframe>
<form action="../formphp/sendMessage.php" method="GET">
    <textarea placeholder="new message" name="message"></textarea>
    <button id="sendbtn" type="submit">send</button>
</form>
<script type="text/javascript">
    window.onload = function() {
    var frameRefreshInterval = setInterval(function() {
        document.getElementById("myframe").src = document.getElementById("myframe").src
    }, 2000);
    // any other code
}
</script>