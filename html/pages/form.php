<main>
    <style>
        main {
            display: flex;
            justify-content: space-evenly;
        }

        #error:not(:empty) {
            color: var(--secondary);
            font-family: monospace;
            font-weight: 600;
            background: var(--quaternary);
            padding: .25rem .5rem;
            border-radius: .5rem;
            margin-bottom: 5%;
            outline: .125rem solid #ff0000;
        }

        form {
            margin-top: 5rem;
            /* background: var(--teriary); */
            background: var(--teriary);
            outline: .125rem solid var(--quaternary);
            border-radius: 1rem;
            width: 15%;
            padding: 2.5%;

            position: relative;
            display: flex;
            flex-direction: column;
        }
        form p {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 5%;
            text-align: center;
            font-size: 2rem;
            font-family: monospace;
        }
        form label {
            font-family: monospace;
            font-size: 1rem;
            text-align: center;
            color: var(--secondary);
            margin-bottom: .5%;
        }
        form input, form select {
            outline: .125rem solid var(--quaternary);
            margin-bottom: 5%;
            background: var(--secondary);
            color: var(--teriary);
            border: none;
            padding: .25rem .5rem;

            border-radius: .5rem;
        }
        form input::placeholder { color: var(--teriary); }
        form input:hover, form select:hover, form input:hover::placeholder { background: var(--teriary); color: var(--secondary); }
        form input#submit {
            position: absolute;
            bottom: 0;
            align-self: center;
            width: 75%;
            font-family: monospace;
            font-weight: 600;
        }
    </style>
    <form action="/src/formHandler.php" method="POST">
        <p>Login</p>
        <span id="error"><?= (isset($_SESSION['LOGIN_ERROR'])) ? $_SESSION['LOGIN_ERROR'] : '' ?></span>
        <label for="email">EMail</label>
        <input required type="email" placeholder="email" name="email">
        <label for="password">Password</label>
        <input required type="password" placeholder="password" name="password">
        <input type="submit" name="submit-login" value="Login" id="submit">
    </form>
    <form action="/src/formHandler.php" method="POST">
        <p>Registration</p>
        <span id="error"><?= (isset($_SESSION['REGISTER_ERROR'])) ? $_SESSION['REGISTER_ERROR'] : '' ?></span>
        <label for="email">EMail</label>
        <input required type="email" placeholder="email" name="email">
        <label for="firstname">Firstname</label>
        <input required type="text" placeholder="firstname" name="firstname">
        <label for="lastname">Lastname</label>
        <input required type="text" placeholder="lastname" name="lastname">
        <label for="password">Password</label>
        <input required type="password" placeholder="password" name="password">
        <input required type="password" placeholder="password confirmation" name="password_conf">
        <label for="role">Role</label>
        <select name="role" id="role">
            <option value="student">Student</option>
            <option value="roleless">Other</option>
        </select>
        <input type="submit" name="submit-register" value="Registreren" id="submit">
    </form>
    <?php
        $_SESSION['LOGIN_ERROR'] = null;
        $_SESSION['REGISTER_ERROR'] = null;
    ?>
</main>