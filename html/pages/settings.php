<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

$settings = Settings::getSettings($_SESSION['user']['id']);

echo "
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='settings-update'>
        <input type='hidden' name='user_id' value='{$_SESSION['user']['id']}'>

        <label for='private'>Private profile: </label>
        <input type='hidden' name='private' value='0'>
        <input type='checkbox' name='private' id='private' value='1'". ($settings['private'] ? 'checked' : '') .">
        <br>
        <label for='invite'>Receive group invites: </label>
        <input type='hidden' name='invite' value='0'>
        <input type='checkbox' name='invite' id='invite' value='1'". ($settings['invite'] ? 'checked' : '') .">
        <br>
        <label for='email'>EMail shown: </label>
        <input type='hidden' name='email' value='0'>
        <input type='checkbox' name='email' id='email' value='1'". ($settings['email'] ? 'checked' : '') .">
        <br>
        <label for='lastname'>Lastname shown: </label>
        <input type='hidden' name='lastname' value='0'>
        <input type='checkbox' name='lastname' id='lastname' value='1'". ($settings['lastname'] ? 'checked' : '') .">
        <br>
        <input type='submit' value='Update'>
    </form>
";