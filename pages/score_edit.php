<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');
if ($_SESSION['user']['role'] != 'teacher') Redirect::to('/index.php?page=scores');

$groupId = DataProcessor::sanitizeData($_GET['id']);
$score = Score::getScore($groupId);

// print_p($score);

echo (isset($_SESSION['ERROR']['SCORE_EDIT_ERROR'])) ? $_SESSION['ERROR']['SCORE_EDIT_ERROR'] : '';
echo "
    <form action='src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='score-edit'>
        <input type='hidden' name='score_id' value='{$score['id']}'>
        <input type='hidden' name='group_id' value='{$groupId}'>
        <label>Current score: {$score['score']}</label><br>
        <input type='number' name='score' value='{$score['score']}' placeholder='Score to add here...'>
        <input type='submit' value='Edit score'>
    </form>
";