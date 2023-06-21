<?php
if (!isset($_SESSION['user'])) Redirect::to('/index.php');

$invites = Request::getInvites($_SESSION['user']['id']);


foreach ($invites as $invite) {
    echo "
        <div class='invite-card'>
            <p id='invite-body'>You've been invited to join \"{$invite['name']}\"</p>
            <form method='POST' action='/src/inc/formHandler.inc.php'>
                <input type='hidden' name='action' value='invite-accept'>
                <input type='hidden' name='user_id' value='{$invite['user_id']}'>
                <input type='hidden' name='group_id' value='{$invite['group_id']}'>
                <input type='submit' value='Accept invite'>
            </form>
            <form method='POST' action='/src/inc/formHandler.inc.php'>
                <input type='hidden' name='action' value='invite-decline'>
                <input type='hidden' name='user_id' value='{$invite['user_id']}'>
                <input type='hidden' name='group_id' value='{$invite['group_id']}'>
                <input type='submit' value='Decline invite'>
            </form>
        </div>
    ";
}