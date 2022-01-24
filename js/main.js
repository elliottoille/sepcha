function checkPasswordMatch() {
    var password = document.getElementById('password'); // Set the password variable to the data inside the element with ID password
    var confirmPassword = document.getElementById('confirmPassword'); // Set the confirmPassword variable to the data inside the element with ID confirmPassword
    var alert = document.getElementById('passwordMatchAlert') // Set the alert variable to the element with ID passwordMatchAlert
    var button = document.getElementById('btn') // Set the button variable to the element with ID btn

    if (password.value == confirmPassword.value) { // If the two values match then
        alert.style.color = '#00ff00'; // Set the alert element's color to green
        alert.textContent = 'Entered passwords match'; // Set the alert element's text to "passwords match"
        button.disabled = false;
        return true;
    } else {
        alert.style.color = '#ff0000'; // Set the alert element's color to red
        alert.textContent = 'Entered passwords do not match'; // Set the alert element's text to "passwords do not match"
        button.disabled = true;
        return false;
    }
}

function changeStyleSheet(stylesheet, property, value) {
    document.getElementById("testButton").innerHTML = "fart bitch";
    document.body.style.fontFamily = value;
}