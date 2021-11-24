<link rel="stylesheet" href="../styles/font.css">
<link rel="stylesheet" href="../styles/form.css">
<form action="formphp/signup.php" method="POST">
    <input type="text" placeholder="username" name="username">

    <input id="password" type="password" placeholder="password" name="password" required>

    <input id="confirmPassword" type="password" placeholder="confirm password" name="confirmPassword" onkeyup="checkPasswordMatch();" required>

    <button id="btn" type="submit">sign up</button>

    <p id="passwordMatchAlert"></p>
</form>
<script src="../js/checkPasswordMatch.js" type="text/javascript"></script>