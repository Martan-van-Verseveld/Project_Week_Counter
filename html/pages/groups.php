<main>

<div id="groups">
<?php

// if (!isset($_SESSION['user'])) die("Access denied!");


$groups = Group::getGroups();

foreach ($groups as $group) {
    echo "
        <div class='group'>
            <p id='group-name'><b>{$group['name']}</b></p>
            <p id='group-description'>{$group['description']}</p>
            <p id='group-member_count'>Members: {$group['member_count']}</p>
    ";

    if ($_SESSION['user']['role'] == 'teacher') {
        echo "<a href='/index.php?page=group_info&id={$group['id']}'>Go to this group</a>";
    } else if (DataProcessor::registeredValue('group_member', [
        'user_id' => $_SESSION['user']['id'], 
        'group_id' => $group['id']
    ])) {
        echo "<a href='/index.php?page=group_info&id={$group['id']}'>Go to your group</a>";
    }

    if (!DataProcessor::registeredValue('group_member', ['user_id' => $_SESSION['user']['id']])) {
        echo "
            <form method='POST' action='/src/inc/formHandler.inc.php' id='request'>
                <input type='hidden' value='{$group['id']}' name='group_id'>
                <input type='hidden' name='action' value='request'>
                <input type='submit' value='Send join request'>
            </form>
        ";
    }

    echo "
        </div><br>
    ";
}

?>  
</div>

</main>