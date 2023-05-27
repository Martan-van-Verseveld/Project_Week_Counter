<main>
    <?php

    $dbh = new Dbh();
    $dbh->startConnection();

    $groupInfo = new GroupInfo();
    $userInfo = new UserInfo();
    $users = $userInfo->getUsers();

    foreach ($users as $user) {
        echo "
            <div id='user_container'>
                <div id='user_info'>
                    <p id='info_title'><a href='/index.php?page=user_form&id={$user['id']}'><b>[{$user['id']}]</b> {$user['firstname']} {$user['lastname']}</a></p>
        "; 

        foreach ($user as $key => $value) {
            echo "<p id='info_$key' class='info_value'><b>[". strtoupper($key) ."]:</b> $value</p>";
        }
        echo "
                </div>
                <div id='group_requests'>
                    <p id='requests_title'><b>Current incomming and outgoing requests:</b></p>
        ";

        $requests = $groupInfo->getGroupOutgoingRequests($user['id']);
        if (empty($requests)) {
            echo "<p id='request_no-requests' class='request_value'><b>No outgoing requests for this user!</b></p>";
        } else {
            foreach ($requests as $request) {
                echo "<p id='request_value' class='request_value'><a href='index.php?page=group_form&id={$request['id']}'><b>[". strtoupper(($request['status'])) ."]</b> {$request['name']}</a></p>";
            }
        }

        echo "
                </div>
            </div>
        ";
    }

    ?>

</main>