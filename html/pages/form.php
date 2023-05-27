<main>
    <form action="/src/formHandler.php" method="POST">
        <p>Login</p>
        <span id="error"><?= (isset($_SESSION['ERROR']['LOGIN_ERROR'])) ? $_SESSION['ERROR']['LOGIN_ERROR'] : '' ?></span>
        <label for="email">EMail</label>
        <input required type="email" placeholder="email" name="email">
        <label for="password">Password</label>
        <input required type="password" placeholder="password" name="password">
        <input type="submit" name="submit-login" value="Login" id="submit">
    </form>
    <form action="/src/formHandler.php" method="POST">
        <p>Registration</p>
        <span id="error"><?= (isset($_SESSION['ERROR']['REGISTER_ERROR'])) ? $_SESSION['ERROR']['REGISTER_ERROR'] : '' ?></span>
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
</main>