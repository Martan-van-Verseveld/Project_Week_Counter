<?php

$users = User::getUsers();

foreach ($users as $user) {
    echo "
        <div class='user-card'>
            <p id='user-id'>{$user['id']}</p>
            <p id='user-name'>{$user['firstname']} {$user['lastname']}</p>
            <p id='user-email'>{$user['email']}</p>
            <a href='/index.php?page=user_info&id={$user['id']}'><button>Go to profile</button></a>
        </div><br>
    ";
}