<?php
if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');


$inbox = Inbox::getInbox($_SESSION['user']['id']);

// print_p($inbox);

echo (isset($_SESSION['ERROR']['FEEDBACK_ERROR'])) ? $_SESSION['ERROR']['FEEDBACK_ERROR'] : '';

if (empty($inbox)) {
    echo "<br>Sorry, you've not received any messages.";
}

foreach ($inbox as $msg) {
    echo "
        <div class='inbox-msg' style='margin-bottom: 2rem;'>
            <form action='src/inc/formHandler.inc.php' method='POST'>
                <input type='hidden' name='action' value='inbox-delete'>
                <input type='hidden' name='inbox_id' value='{$msg['id']}'>
                <input type='hidden' name='user_id' value='{$_SESSION['user']['id']}'>
                <input type='submit' value='X'>
            </form>
            <p id='inbox-title'>{$msg['title']}</p>
            <p id='inbox-body'>{$msg['body']}</p>
        </div>
    ";
}
