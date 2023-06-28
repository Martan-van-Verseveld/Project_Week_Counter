<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

if ($_SESSION['user']['role'] != 'teacher') Redirect::to('/index.php?page=home');

echo "
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='theme-create'>
        <input type='text' name='title' placeholder='Theme title here...'>
        <textarea name='description' placeholder='Theme description here...'></textarea>
        <input type='submit' value='Create theme'>
    </form>
";
