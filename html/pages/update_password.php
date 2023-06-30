<?php

if ($_GET['id'] != $_SESSION['user']['id']) {
    Redirect::to();
}

$user = User::getUser($_GET['id']);

echo "
    <form id='pass_update' action='src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='password-update'>
        <input type='hidden' name='user_id' value='{$_GET['id']}'>
        <label for=''>Update the password for {$user['email']}</label>
        <input type='password' name='password'>
        <input type='password' name='password_conf'>
        <input type='submit' value='Update password'>
    </form>
    <a href='index.php?page=profile&id={$user['id']}'>Back</a>
";