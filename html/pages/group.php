<main>
    <?php

    $dbh = new Dbh();
    $dbh->startConnection();

    $groupInfo = new GroupInfo();
    $groups = $groupInfo->getGroups();

    foreach ($groups as $group) {
        echo "
            <div id='group_container'>
                <div id='group_info'>
                    <p id='info_title'><a href='/index.php?page=group_form&id={$group['id']}'><b>[{$group['id']}]</b> {$group['name']}:</a></p>
        "; 

        foreach ($group as $key => $value) {
            echo "<p id='info_$key' class='info_value'><b>[". strtoupper($key) ."]:</b> $value</p>";
        }

        echo "
                </div>
                <div id='group_requests'>
                    <p id='requests_title'><b>Current incomming and outgoing requests:</b></p>
        ";

        $requests = $groupInfo->getGroupIncommingRequests($group['id']);
        if (empty($requests)) {
            echo "<p id='request_no-requests' class='request_value'><b>No requests to join this group!</b></p>";
        } else {
            foreach ($requests as $request) {
                echo "<p id='request_value' class='request_value'><a href='index.php?page=user_form&id={$request['user_id']}'><b>[". strtoupper(($request['status'])) ."]</b> {$request['email']}</a></p>";
            }
        }

        echo "
                </div>
            </div>
        ";
    }

    ?>

</main>
