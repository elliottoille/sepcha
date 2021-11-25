<link rel="stylesheet" href="../../styles/font.css">
<link rel="stylesheet" href="../../styles/contactsTab.css">
<ul>
    <li><form method="POST" action="../formphp/newContact.php">
        <input id="searchBox" type="text" name="contactUsername" placeholder="contact username"/>
        <input id="searchBtn" type="submit" value="search">
    </form></li>
    <?php
        include '../../php/main.php';
        
    ?>
</ul>