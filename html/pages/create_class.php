<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

if ($_SESSION['user']['role'] != 'teacher') Redirect::to('/index.php?page=home');

echo "
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='class-create'>
        <label for='name'>Create your group!</label><br>
        <input type='text' name='name' id='name' placeholder='Class name here...'><br>
        <input type='submit' value='Create your class now'>
    </form>
";
