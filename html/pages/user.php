<main>
    <style>
        main {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        div#user_container {
            margin-top: 2.5%;
            background: var(--teriary);
            outline: .125rem solid var(--quaternary);
            border-radius: 1rem;
            width: 25%;
            padding: 2.5%;
        }
        div#user_info {
            background: none;
            margin-bottom: 5%;

            position: relative;
            display: flex;
            flex-direction: column;
        }
        p#info_title, p#requests_title {
            font-family: monospace;
            margin-bottom: 2.5%;
            color: var(--quaternary);
            font-weight: 600;
        }
        p.info_value, p.request_value {
            color: var(--secondary);
            font-family: monospace;
            overflow-wrap: break-word;
        }
    </style>

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
                    <p id='info_title'><b>[{$user['id']}]</b> {$user['firstname']} {$user['lastname']}</p>
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
                foreach ($request as $key => $value) {
                    echo "<p id='request_$key' class='request_value'><b>[". strtoupper($key) ."]:</b> $value</p>";
                }
            }
        }

        echo "
                </div>
            </div>
        ";
    }

    ?>

</main>