<main>
    <?php

    $groupId = DataProcessor::sanitizeData($_GET['id']);
    $groupInfo = Group::getGroupInfo($groupId);

    $isAdmin = Member::isOwner($_SESSION['user']['id'], $groupId);

    if ($_SESSION['user']['role'] != 'teacher') {
        if (!Member::isMember($_SESSION['user']['id'], $groupId)) {
            die("Access denied!");
        }
    }

    echo "
        <div id='group-container'>
    ";

    if ($isAdmin) echo "<a href='/index.php?edit_group&id={$groupInfo['Group']['id']}'>Edit your group</a>";

    echo "
            <div id='group-info'>
                <p id='group-name'>{$groupInfo['Group']['name']}</p>
                <p id='group-description'>{$groupInfo['Group']['description']}</p>
            </div>
            <br>
            <div id='users-container'>
    ";

    foreach ($groupInfo['Users'] as $info) {
        // print_p($info);
        if ($info['id'] === $_SESSION['user']['id']) {
            echo "
                <form action='/src/inc/formHandler.inc.php' method='POST'>
                    <input type='hidden' name='action' value='member-leave'>
                    <input type='hidden' name='user_id' value='{$info['id']}'>
                    <input type='hidden' name='group_id' value='{$groupInfo['Group']['id']}'>
                    <input type='submit' value='leave-group'>
                </form>
            ";
        } else {
            echo "
                <form action='/src/inc/formHandler.inc.php' method='POST'>
                    <input type='hidden' name='action' value='member-remove'>
                    <input type='hidden' name='user_id' value='{$info['id']}'>
                    <input type='hidden' name='group_id' value='{$groupInfo['Group']['id']}'>
                    <input type='submit' value='remove-member'>
                </form>
                <form action='/src/inc/formHandler.inc.php' method='POST'>
                    <input type='hidden' name='action' value='member-owner'>
                    <input type='hidden' name='user_id' value='{$info['id']}'>
                    <input type='hidden' name='group_id' value='{$groupInfo['Group']['id']}'>
                    <input type='submit' value='owner-member'>
                </form>
            ";
        }
        echo "
            <hr>
            <p id='user-email'>{$info['email']} <span id='user-group_role'>{$info['group_role']}</span></p>
            <p id='user-name'>{$info['firstname']} {$info['lastname']}</p>
            <br>
        ";
    }

    echo (isset($_SESSION["ERROR"]["REQUEST_ERROR"])) ? $_SESSION["ERROR"]["REQUEST_ERROR"] : "";
    echo (isset($_SESSION["ERROR"]["MEMBER_ERROR"])) ? $_SESSION["ERROR"]["MEMBER_ERROR"] : "";
    
    foreach ($groupInfo['Users'] as $user) {
        if ($isAdmin) {
            foreach ($groupInfo['Requests'] as $request) {
                echo "
                    <div class='request-container'>
                        <p id='request-user'><span id='user-group_role'>{$request['user_id']}</span></p>
                        <form method='POST' action='/src/inc/formHandler.inc.php'>
                            <input type='hidden' name='action' value='request-accept'>
                            <input type='hidden' value='{$request['user_id']}' name='user_id'>
                            <input type='hidden' value='{$groupInfo['Group']['id']}' name='group_id'>
                            <input type='submit' value='Accept'>
                        </form>
                        <form method='POST' action='/src/inc/formHandler.inc.php'>
                            <input type='hidden' name='action' value='request-decline'>
                            <input type='hidden' value='{$request['user_id']}' name='user_id'>
                            <input type='hidden' value='{$groupInfo['Group']['id']}' name='group_id'>
                            <input type='submit' value='Decline'>
                        </form>
                    </div>
                ";
            }
        }
    }

    ?>
</main>