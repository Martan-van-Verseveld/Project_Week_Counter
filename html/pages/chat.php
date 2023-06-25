<?php

if (!isset($_SESSION['user'])) {
    Redirect::to('/index.php?page=home');
}

$chats = Chat::getChats($_SESSION['user']['id']);
$users = User::getUsers();

// print_p($chats);

$showChatting = false;
foreach ($users as $user) {
    $showChatting = false;
    if (Settings::isChatable($user['id']) && $user['id'] != $_SESSION['user']['id'] && Chat::hasChatted($_SESSION['user']['id'], $user['id'])) {
        $showChatting = true;
        break;
    }
}

if ($showChatting) {
    echo "
        <div>
            <select id='chat_selector' name='chat_selector'>
    ";
    foreach ($users as $user) {
        if (Settings::isChatable($user['id']) && $user['id'] != $_SESSION['user']['id'] && Chat::hasChatted($_SESSION['user']['id'], $user['id'])) {
            echo "
                <option value='{$user['id']}'>{$user['name']}</option>
            ";
        }
    }
    echo "
            </select>
            <input id='chat_selector_submit' type='submit' value='open this chat'>
        </div>
    ";
}

foreach ($chats as $chat) {
    $sender = ($chat['sender_id'] == $_SESSION['user']['id']) ? 'You' : $chat['sender_name'];
    $sender_id = ($chat['sender_id'] == $_SESSION['user']['id']) ? $chat['sender_id'] : $chat['recipient_id'];

    $recipient = ($chat['sender_id'] == $_SESSION['user']['id']) ? $chat['recipient_name'] : $chat['sender_name'];
    $recipient_id = ($chat['sender_id'] == $_SESSION['user']['id']) ? $chat['recipient_id'] : $chat['sender_id'];

    $body = html_entity_decode($chat['body']);

    echo "
        <div class='chat-msg'>
            <p class='chat-msg-item' id='recipient'><a href='/index.php?page=chat_room&s=$sender_id&r=$recipient_id'>$recipient</a></p>
            <p class='chat-msg-item' id='name'>$sender sent:</p>
            <p class='chat-msg-item' id='body'>{$chat['body']}</p>
            <p class='chat-msg-item' id='sent'>{$chat['sent']}</p>
        </div><br>
    ";
}

?>

<script>
    submit = document.getElementById('chat_selector_submit');
    select = document.getElementById('chat_selector');

    submit.addEventListener('click', function(e) {
        event.preventDefault();
        console.log(select.value);
        window.location.href = `/index.php?page=chat_room&s=<?= $_SESSION['user']['id'] ?>&r=${select.value}`;
    });
</script>
