<main>
<?php

$userId = $_GET['id'];
if ($_SESSION['user']['id'] != $userId || !isset($_SESSION['user']) || !DataProcessor::registeredValue('user', [
    'id' => $_SESSION['user']['id'], 
    'role' => 'teacher'
])) {
    if (User::isPrivate($userId)) header('Location: '. $_SERVER['HTTP_REFERER']);
}

$user = User::getUser($userId);

// print_p($user);

$email = $user['settings']['email'] ? "{$user['email']}" : "";
$name = $user['firstname'] . ($user['settings']['lastname'] ? " {$user['lastname']}" : "");

if ($_SESSION['user']['id'] == $userId) {
    echo "<a href='/index.php?page=profile_edit&id=$userId'>Edit your profile</a>";
}

echo "
    <div class='user'>
        <p class='user-item' id='name'>$name</p>
        <p class='user-item' id='email'>$email</p>
        <p class='user-item' id='description'>{$user['description']}</p>
    </div>
";

if (!empty($user['group'])) {
    echo "
        <br><hr><br>
        <div class='group'>
            <p class='group-item' id='name'><a href='/index.php?page=group&id={$user['group']['id']}'>{$user['group']['name']}</a></p>
            <p class='group-item' id='role'>{$user['group']['member_role']}</p>
        </div>
    ";
}

?>
</main>