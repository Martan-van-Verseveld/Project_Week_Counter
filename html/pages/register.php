<?php
    if (!empty($_SESSION['user'])) Redirect::to("/index.php?page=home");
?>
<main class="form_wrapper">
    <form action="/src/inc/formHandler.inc.php" method="POST" id="reg_form">
        <input type="hidden" name="action" value="register">
        <h2>Registreren</h2>
        <span id="error"><?= (isset($_SESSION['ERROR']['REGISTER_ERROR'])) ? $_SESSION['ERROR']['REGISTER_ERROR'] : '' ?></span>
        <input type="email" name="email" placeholder="Email"/>
        <input required type="text" placeholder="firstname" name="firstname"/>
        <input required type="text" placeholder="lastname" name="lastname"/>
        <input type="password" name="password" placeholder="Password"/>
        <input type="password" name="password_conf" placeholder="Confirm password"/>
        <div class="roles">
            <label>
                <input type="radio" name="role" value="student"/>
                <span>Student</span>
            </label>
            <label>
                <input type="radio" name="role" value="teacher"/>
                <span>Teacher</span>
            </label>
        </div>
        <input type="submit" value="Registreren">
        <a href="/index.php?page=login">Already have an account? Login here.</a>
    </form>
</main>
