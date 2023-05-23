function togglePassword(element) {
    var passwordInput = element.parentNode.querySelector("input[type='password']") || element.parentNode.querySelector("input[type='text']");
    passwordInput.type = (passwordInput.type === "password") ? "text" : "password";

    if (element.querySelector("i").className === "bi-eye-slash") {
        element.querySelector("i").classList.remove("bi-eye-slash");
        element.querySelector("i").classList.add("bi-eye-slash-fill");
    } else {
        element.querySelector("i").classList.remove("bi-eye-slash-fill");
        element.querySelector("i").classList.add("bi-eye-slash");
    }
}

function checkPasswords() {
    var password = document.getElementById("password").value;
    var password_conf = document.getElementById("password_conf").value;
    var passwordsError = document.getElementById('passwords_error');
    

    if (password != password_conf) {
        passwordsError.innerHTML = "Passwords don't match!";
        passwordsError.style.color = "red";

        document.querySelector('input[type="submit"]').disabled = true;
    }
    if (password == password_conf) {
        passwordsError.innerHTML = "Passwords match!";
        passwordsError.style.color = "green";

        document.querySelector('input[type="submit"]').disabled = false;
    };
}