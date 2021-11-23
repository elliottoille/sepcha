<link rel="stylesheet" href="../styles/font.css">
<link rel="stylesheet" href="../styles/form.css">
<form action="formphp/signup.php" method="POST">
    <label for="username">username</label>
    <input type="text" placeholder="username" name="username">

    <label for="password">password</label>
    <input id="password" type="password" placeholder="password" name="password" required>

    <label for="confirmPassword">confirm password</label>
    <input id="confirmPassword" type="password" placeholder="confirm password" name="confirmPassword" onkeyup="checkPasswordMatch();" required>

    <p id="passwordMatchAlert"></p>

    <button id="btn" type="submit">sign up</button>
</form>
<script src="../js/checkPasswordMatch.js" type="text/javascript"></script>