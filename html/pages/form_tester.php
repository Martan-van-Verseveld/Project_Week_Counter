<main>
    <style>
        body {
            background: #ffffff;
        }
        form {
            font-family: Roboto, sans-serif;

            display: flex;
            flex-direction: column;
            width: 16rem;
            margin: 0 auto 10rem auto;
            padding: 1rem;

            outline: .2rem darkslategrey solid;
            border-radius: .1rem;
        }
        form * > input:focus { outline: none; }
        form > input, form > select, form > p { margin-bottom: .5rem; }
        form > input, form > select {
            border: none;
            border-radius: .1rem;
            outline: .1rem darkslategrey solid;
            
            background: none;

            padding: .1rem;
        }
        form span {
            display: grid;
            grid-auto-flow: column;
            grid-template-columns: 90% 10%;
            height: 100%;
            
            padding: .1rem;
            
            margin-bottom: .5rem;
            border: none;
            border-radius: .1rem;
            outline: .1rem darkslategrey solid;
        }
        form span > * {
            height: 100%;
            border-radius: .1rem;
            border: none;
            background: none;
        }
        #form_error {
            color: red;
        }
    </style>
    <form action="/inc/form_handler.php" method="POST" id="register" onload="passwordDifference();">
        <p id="form_error"><?= (!empty($_SESSION['FORM_ERROR'])) ? $_SESSION['FORM_ERROR'] : ""; ?></p>
        <label for="firstname">E-Mail</label>
        <input required minlength="5" maxlength="32" type="email" name="email" id="email">
        <label for="firstname">Voornaam</label>
        <input required minlength="2" maxlength="8" type="text" name="firstname" id="firstname">
        <label for="lastname">Achternaam</label>
        <input required minlength="2" maxlength="24" type="text" name="lastname" id="lastname">
        <label for="password">Wachtwoord</label>
        <span><input oninput='checkPasswords()' required minlength="8" maxlength="32" type="password" name="password" id="password"><button type="button" onclick="togglePassword(this);"><i class="bi-eye-slash"></i></button></span>
        <label for="password_conf">Wachtwoord 2</label>
        <span><input oninput='checkPasswords()' required minlength="8" maxlength="32" type="password" name="password_conf" id="password_conf"><button type="button" onclick="togglePassword(this);"><i class="bi-eye-slash"></i></button></span>
        <p id="passwords_error">&nbsp;</p>
        <label for="role">Rol</label>
        <select name="role" id="role">
            <option value="student">Student</option>
            <option value="roleless">Other</option>
        </select>
        <input type="submit" name="submit_register" value="Registreren">
        <a href="#login">login</a>
    </form>
    <form action="/inc/form_handler.php" method="POST" id="login">
        <p id="form_error"><?= (!empty($_SESSION['FORM_ERROR'])) ? $_SESSION['FORM_ERROR'] : ""; ?></p>
        <label for="firstname">E-Mail</label>
        <input required minlength="2" maxlength="32" type="email" name="email" id="email">
        <label for="password">Wachtwoord</label>
        <span><input required minlength="8" maxlength="32" type="password" name="password" id="password"><button type="button" onclick="togglePassword(this);"><i class="bi-eye-slash"></i></button></span>
        <input type="submit" name="submit_login" value="Login">
        <a href="#register">register</a>
    </form>
</main>
<?php $_SESSION["FORM_ERROR"] = null; ?>