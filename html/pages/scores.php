<?php

$orderType = (isset($_GET['o'])) ? DataProcessor::sanitizeData($_GET['o']) : null;
$scores = Score::getScores($orderType);

print_p($scores);

foreach ($scores as $score) {
    echo "
        <div class='group'>
            <p id='group-name'>{$score['name']}</p>
            <p id='group-rank'>rank: {$score['rank']}</p>
            <p id='group-score'>Score: {$score['score']}</p>
            <p id='group-member_count'>Members: {$score['member_count']}</p>
        </div><br>
    ";
}
