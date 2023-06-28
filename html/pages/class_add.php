<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');
if ($_SESSION['user']['role'] != 'teacher') Redirect::to(Session::get('REFERER'));
if (!isset($_GET['id'])) Redirect::to('/index.php?page=classes');
$classId = DataProcessor::sanitizeData($_GET['id']);


$users = User::getUsers();

foreach ($users as $user) {
    if (!DataProcessor::registeredValue('class_member', [
        'user_id' => $user['id'],
        'class_id' => $classId
    ]) && $user['role'] != 'teacher') {
        echo "
            <div class='user'>
                <p id='user-name'>{$user['name']}</p>
                <form method='POST' action='/src/inc/formHandler.inc.php'>
                    <input type='hidden' name='action' value='class-add'>
                    <input type='hidden' name='user_id' value='{$user['id']}'>
                    <input type='hidden' name='class_id' value='{$classId}'>
                    <input type='submit' value='Add user to class'>
                </form>
            </div>
        ";
    }
}

echo "<a href='/index.php?page=class&id={$classId}'>Back</a>";
