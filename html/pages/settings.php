<?php

if (empty($_SESSION['user'])) Redirect::to("/index.php?page=home");

$settings = Settings::getSettings($_SESSION['user']['id']);

echo "
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='settings-update'>

        <label for='private'>Private profile: </label>
        <input type='hidden' name='private' value='0'>
        <input type='checkbox' name='private' id='private' value='1'". ($settings['private'] ? 'checked' : '') .">
        <br>
        <label for='invites'>Get group join invites: </label>
        <input type='hidden' name='invites' value='0'>
        <input type='checkbox' name='invites' id='invites' value='1'". ($settings['invites'] ? 'checked' : '') .">
        <br>
        <input type='submit' value='Update'>
    </form>
";