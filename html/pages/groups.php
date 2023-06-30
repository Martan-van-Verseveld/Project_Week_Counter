<main>

<div id="groups">
<?php
if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');


$groups = Group::getGroups();

if (isset($_SESSION['user']) && !DataProcessor::registeredValue('group_member', [
    'user_id' => $_SESSION['user']['id']
])) {
    echo "
        <div class='create-group'>
            <a href='index.php?page=create_group'>Create a group</a>
        </div>
    ";
}


foreach ($groups as $group) {
    $score = Score::getScore($group['id']);
    
    echo "
        <div class='group'>
            <p id='group-name'><b>{$group['name']}</b></p>
            <p id='group-description'>{$group['description']}</p>
            <p id='group-member_count'>Members: {$group['member_count']}</p>
            <p id='group-score'>Score: {$score['score']}</p>
    ";

    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'teacher') {
        echo "<a href='index.php?page=group&id={$group['id']}'>Go to this group</a>";
    } else if (isset($_SESSION['user']) && DataProcessor::registeredValue('group_member', [
        'user_id' => $_SESSION['user']['id'], 
        'group_id' => $group['id']
    ])) {
        echo "<a href='/index.php?page=group&id={$group['id']}'>Go to your group</a>";
    }

    if (isset($_SESSION['user']) && !DataProcessor::registeredValue('group_member', ['user_id' => $_SESSION['user']['id']])) {
        echo "
            <form method='POST' action='src/inc/formHandler.inc.php' id='request'>
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