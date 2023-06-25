<?php

$userId = DataProcessor::sanitizeData($_GET['id']);
if ($_SESSION['user']['id'] != $userId || !isset($_SESSION['user']) || !DataProcessor::registeredValue('user', [
    'id' => $_SESSION['user']['id']
])) {
    header('Location: '. $_SERVER['HTTP_REFERER']);
}

$user = User::getUser($userId);

echo "
    <a href='/index.php?page=update_password&id=$userId'>Update your password</a>
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='profile-edit'>
        <input type='hidden' name='user_id' value='{$user['id']}'>
        <input type='text' name='firstname' value='{$user['firstname']}'>
        <input type='text' name='lastname' value='{$user['lastname']}'>
        <textarea name='description'>{$user['description']}</textarea>
        <input type='text' name='email' value='{$user['email']}'>
        <input type='submit' value='Update profile'>
    </form>
    <a href='/index.php?page=profile&id={$user['id']}'>Back</a>
";
