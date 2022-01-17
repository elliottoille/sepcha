<link rel="stylesheet" href="../../styles/font.php">
<link rel="stylesheet" href="../../styles/contactsTab.css">
<ul>
    <li><form method="POST" action="../formphp/newContact.php">
        <input id="searchBox" type="text" name="contactUsername" placeholder="contact username"/>
        <input id="searchBtn" type="submit" value="search">
    </form></li>
    <?php
        include '../../php/main.php';
        if ( isset($_SESSION["currentUser"]) ) {
            $currentUser = $_SESSION["currentUser"];
            $currentUser->renderContacts();
        } else {
            echo "you are not logged in";
        }
    ?>
</ul>