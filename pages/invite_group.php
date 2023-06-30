<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

if (!DataProcessor::registeredValue('group_member', [
    'user_id' => $_SESSION['user']['id'],
    'role' => 'owner'
])) Redirect::to(Session::get('REFERER'));

$users = User::getUsers();
$group = Group::getUserGroup($_SESSION['user']['id']);

// print_p($users);

foreach ($users as $user) {
    if (Settings::isInviteable($user['id']) && !DataProcessor::registeredValue('group_member', [
        'user_id' => $user['id']
    ]) && !DataProcessor::registeredValue('group_request', [
        'user_id' => $user['id'],
        'group_id' => $group['id']
    ])) {
        echo "
            <div class='user'>
                <p id='user-name'>{$user['name']}</p>
                <form method='POST' action='src/inc/formHandler.inc.php'>
                    <input type='hidden' name='action' value='invite-user'>
                    <input type='hidden' name='user_id' value='{$user['id']}'>
                    <input type='hidden' name='group_id' value='{$group['id']}'>
                    <input type='submit' value='Invite this user to your group'>
                </form>
            </div>
        ";
    }
}

echo "<a href='/index.php?page=group&id={$group['id']}'>Back</a>";
