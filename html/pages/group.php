<main>
<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

$groupId = DataProcessor::sanitizeData($_GET['id']);
$groupInfo = Group::getGroupInfo($groupId);
$group = $groupInfo['Group'];
$score = Score::getScore($group['id']);

$isAdmin = Member::isOwner($_SESSION['user']['id'], $groupId) || $_SESSION['user']['role'] == 'teacher';

if (!DataProcessor::registeredValue('group_info', [
    'id' => $groupId
])) die("Group not found!");

if ($_SESSION['user']['role'] != 'teacher' ) {
    if (!Member::isMember($_SESSION['user']['id'], $groupId)) {
        die("Access denied!");
    }
}


$theme = (isset($group['theme_id'])) ? Theme::getTheme($group['theme_id']) : [];
// print_p($theme);


echo (isset($_SESSION['ERROR']['GROUP_ERROR'])) ? $_SESSION['ERROR']['GROUP_ERROR'] : '';
echo "
    <div id='group-container'>
        <a href='index.php?page=feedback&type=group&id=$groupId'>View feedback</a>    
";
if ($isAdmin) {
    echo "
        <a href='index.php?page=edit_group&id={$group['id']}'>Edit your group</a>
        <a href='index.php?page=invite_group'>Invite people to group</a>
    ";
}
if ($_SESSION['user']['role'] == 'teacher') {
    echo "
        <a href='index.php?page=feedback_give&id={$_GET['id']}&type=group'>Give this group feedback</a>
    ";
}
if (!empty($theme)) {
    echo "
        <div id='theme-info'>
            <p id='theme-title'><a href='index.php?page=theme&id={$theme['id']}'>Registered theme: {$theme['title']}</a></p>
        </div>
    ";
}
echo "
        <div id='group-info'>
            <p id='group-name'>{$group['name']}</p>
            <p id='group-description'>{$group['description']}</p>
        </div>
        <br>
        <div id='score-info'>
            <p id='group-score'>Score: {$score['score']}</p>
        </div>
        <br>
        <div id='users-container'>
";

foreach ($groupInfo['Users'] as $info) {
    // print_p($info);
    if ($info['id'] === $_SESSION['user']['id']) {
        echo "
            <form action='src/inc/formHandler.inc.php' method='POST'>
                <input type='hidden' name='action' value='member-leave'>
                <input type='hidden' name='user_id' value='{$info['id']}'>
                <input type='hidden' name='group_id' value='{$group['id']}'>
                <input type='submit' value='leave-group'>
            </form>
        ";
    } else {
        if ($isAdmin) {
            echo "
                <form action='src/inc/formHandler.inc.php' method='POST'>
                    <input type='hidden' name='action' value='member-remove'>
                    <input type='hidden' name='user_id' value='{$info['id']}'>
                    <input type='hidden' name='group_id' value='{$group['id']}'>
                    <input type='submit' value='remove-member'>
                </form>
                <form action='/src/inc/formHandler.inc.php' method='POST'>
                    <input type='hidden' name='action' value='member-owner'>
                    <input type='hidden' name='user_id' value='{$info['id']}'>
                    <input type='hidden' name='group_id' value='{$group['id']}'>
                    <input type='submit' value='owner-member'>
                </form>
            ";
        }
    }
    echo "
        <hr>
        <p id='user-email'>{$info['email']} <span id='user-group_role'>{$info['group_role']}</span></p>
        <p id='user-name'>{$info['firstname']} {$info['lastname']}</p>
        <br>
    ";
}

// echo (isset($_SESSION["ERROR"]["REQUEST_ERROR"])) ? $_SESSION["ERROR"]["REQUEST_ERROR"] : "";
// echo (isset($_SESSION["ERROR"]["MEMBER_ERROR"])) ? $_SESSION["ERROR"]["MEMBER_ERROR"] : "";

foreach ($groupInfo['Users'] as $user) {
    if ($isAdmin) {
        foreach ($groupInfo['Requests'] as $request) {
            echo "
                <div class='request-container'>
                    <p id='request-user'><span id='user-group_role'>{$request['user_id']}</span></p>
                    <form method='POST' action='src/inc/formHandler.inc.php'>
                        <input type='hidden' name='action' value='request-accept'>
                        <input type='hidden' value='{$request['user_id']}' name='user_id'>
                        <input type='hidden' value='{$group['id']}' name='group_id'>
                        <input type='submit' value='Accept'>
                    </form>
                    <form method='POST' action='src/inc/formHandler.inc.php'>
                        <input type='hidden' name='action' value='request-decline'>
                        <input type='hidden' value='{$request['user_id']}' name='user_id'>
                        <input type='hidden' value='{$group['id']}' name='group_id'>
                        <input type='submit' value='Decline'>
                    </form>
                </div>
            ";
        }
    }
}

?>
</main>