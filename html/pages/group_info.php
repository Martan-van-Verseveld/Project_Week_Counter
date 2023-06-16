<main>
    <?php

    $groupId = DataProcessor::sanitizeData($_GET['id']);

    $groupInfo = Group::getGroupInfo($groupId);

    foreach ($groupInfo['Users'] as $user) {
        if ($user['id'] == $_SESSION['user']['id'] && $user['group_role'] == 'owner') {
            echo "Is Admin!";
        }
    }

    if ($_SESSION['user']['role'] != 'teacher') {
        if (!DataProcessor::registeredValue('group_member', [
            'user_id' => $_SESSION['user']['id'],
            'group_id' => $groupId
        ])) {
            die("Access denied!");
        }
    }

    echo "
        <div id='group-container'>
            <div id='group-info'>
                <p id='group-name'>{$groupInfo['Group']['name']}</p>
                <p id='group-description'>{$groupInfo['Group']['description']}</p>
            </div>
            <br>
            <div id='users-container'>
    ";

    foreach ($groupInfo['Users'] as $info) {
        // print_p($info);
        echo "
            <form action='/src/inc/formHandler.inc.php' method='POST'>
                <input type='hidden' name='action' value='member_remove'>
                <input type='hidden' name='user_id' value='{$info['id']}'>
                <input type='hidden' name='group_id' value='{$groupInfo['Group']['id']}'>
                <p id='user-email'>{$info['email']} <span id='user-group_role'>{$info['group_role']}</span></p>
                <p id='user-name'>{$info['firstname']} {$info['lastname']}</p>
                <input type='submit' value='Remove'>
            </form>
            <br>
        ";
    }

    // echo "
    //         </div>
    //     </div>
    //     <form action='/src/inc/formHandler.inc.php' method='POST'>
    //     <input type='hidden' name='action' value='request'>
    //         <input type='hidden' name='group_id' value='{$groupInfo['Group']['id']}'>
    //         <input type='submit' value='Send join request!'>
    //     </form>
    // ";

    echo ($_SESSION["ERROR"]["REQUEST_ERROR"]) ? $_SESSION["ERROR"]["REQUEST_ERROR"] : "";
    // echo ($_SESSION["ERROR"]["MEMBER_ERROR"]) ? $_SESSION["ERROR"]["MEMBER_ERROR"] : "";
    
    foreach ($groupInfo['Users'] as $user) {
        if ($user['id'] == $_SESSION['user']['id'] && $user['group_role'] == 'owner') {
            foreach ($groupInfo['Requests'] as $request) {
                echo "
                    <div class='request-container'>
                        <p id='request-user'><span id='user-group_role'>{$request['user_id']}</span></p>
                        <form method='POST' action='/src/inc/formHandler.inc.php'>
                        <input type='hidden' name='action' value='request_decline'>
                            <input type='hidden' value='{$request['user_id']}' name='user_id'>
                            <input type='hidden' value='{$groupInfo['Group']['id']}' name='group_id'>
                            <input type='submit' value='Decline'>
                        </form>
                        <form method='POST' action='/src/inc/formHandler.inc.php'>
                        <input type='hidden' name='action' value='request_accept'>
                            <input type='hidden' value='{$request['user_id']}' name='user_id'>
                            <input type='hidden' value='{$groupInfo['Group']['id']}' name='group_id'>
                            <input type='submit' value='Accept'>
                        </form>
                    </div>
                ";
            }
        }
    }
    
    ?>
</main>