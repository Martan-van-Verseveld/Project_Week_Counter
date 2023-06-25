<?php

$orderType = (isset($_GET['o'])) ? DataProcessor::sanitizeData($_GET['o']) : null;
$scores = Score::getScores($orderType);

// print_p($scores);

if (DataProcessor::registeredValue('user', [
    'id' => $_SESSION['user']['id'],
    'role' => 'teacher'
])) {
    echo "<a href=''>Add scores</a>";
}

foreach ($scores as $score) {
    echo "
        <div class='group'>
    ";

    if (isset($_SESSION['user']) && DataProcessor::registeredValue('group_member', [
        'user_id' => $_SESSION['user']['id'], 
        'group_id' => $score['group_id']
    ])) {
        echo "<p id='group-name'>{$score['name']} [Your group]</p>";
    } else {
        echo "<p id='group-name'>{$score['name']}</p>";
    }

    echo "
            <p id='group-rank'>rank: {$score['rank']}</p>
            <p id='group-score'>Score: {$score['score']}</p>
            <p id='group-member_count'>Members: {$score['member_count']}</p>
        </div><br>
    ";
}
