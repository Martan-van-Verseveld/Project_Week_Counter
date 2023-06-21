<main>
<?php

if (!isset($_SESSION['user'])) die("Access denied!");

$user = User::getUser($_GET['id']);


$query = "
    SELECT *
    FROM `group_member`
    WHERE user_id = :userId;
";

// Execute statement
$sto = Dbh::getConnection()->prepare($query);
$sto->execute([
    ':userId' => $_SESSION['user']['id']
]);

$memberFetch = $sto->fetch(PDO::FETCH_ASSOC);

$userGroup = Group::getUserGroup($_GET['id']);

echo "
    <div class='user'>
        <p id='user-id'>{$user['id']}</p>
        <p id='user-name'>{$user['firstname']} {$user['lastname']}</p>
        <p id='user-email'><a href='mailto:{$user['email']}'>{$user['email']}</a></p>
";

$data = [
    'user_id' => $user['id'], 
    'group_id' => $memberFetch['group_id']
];
if (Member::isOwner($_SESSION['user']['id'], $memberFetch['group_id']) 
    && !DataProcessor::registeredValue('group_request', $data)
    && !DataProcessor::registeredValue('group_member', $data)
) {
    echo "
        <form method='POST' action='/src/inc/formHandler.inc.php'>
            <input type='hidden' name='action' value='invite-user'>
            <input type='hidden' name='user_id' value='{$user['id']}'>
            <input type='hidden' name='group_id' value='{$memberFetch['group_id']}'>
            <input type='submit' value='Invite this user to your group'>
        </form>
    ";
}

if (DataProcessor::registeredValue('group_member', ['user_id' => $user['id']])) {
    echo "
        <p id='user-group-name'>Member of <a href='/index.php?page=group_info&id={$userGroup['id']}'>\"{$userGroup['name']}\"</a></p>
    ";
}

echo "
    </div><br>
";

?>
</main>