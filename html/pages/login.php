<?php
    if (!empty($_SESSION['user'])) Redirect::to("/index.php?page=home");
?>
<main class="form_wrapper">
    <form action="src/inc/formHandler.inc.php" method="POST" id="login_form">
        <input type="hidden" name="action" value="login">
        <h2>Account login</h2>
        <span id="error"><?= (isset($_SESSION['ERROR']['LOGIN_ERROR'])) ? $_SESSION['ERROR']['LOGIN_ERROR'] : '' ?></span>
        <input type="email" name="email" placeholder="Email"/>
        <input type="password" name="password" placeholder="Password"/>
        <div class="opts">
            <div>
            <input type="checkbox" name="remember"/>
            <span>Remember me</span>
            </div>
            <a href="reset.html">Forgot password?</a>
        </div>
        <input type="submit" value="Inloggen"/>
        <a href="index.php?page=register">Don't have an account yet? Register here.</a>
    </form>
</main>
