<?php
if (!isset($_SESSION['user'])) Redirect::to('/index.php');


$inbox = Inbox::getInbox($_SESSION['user']['id']);

// print_p($inbox);

// foreach ($inbox['invites'] as $msg) {
//     echo "
//         <div class='inbox-msg'>
//             <p class='inbox-msg-item' id='title'>{$msg['user_id']}</p>
//             <p class='inbox-msg-item' id='body'>{$msg['body']}</p>
//         </div>
//     ";
// }
