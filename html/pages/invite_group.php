<?php

if (DataProcessor::registeredValue('group_member', [
    'user_id' => $_SESSION['user']['id'],
    'role' => 'owner'
])) Redirect::to();

$users = User::getInvUsers();

print_p($users);

foreach ($users as $user) {
    echo "
        <div class='user'>
            <p id='user-name'>{$user['firstname']} {$user['lastname']}</p>
        </div>
    ";
}
