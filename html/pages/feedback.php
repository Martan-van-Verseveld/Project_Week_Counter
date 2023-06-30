<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('index.php?page=home');
if (empty($_GET['type']) || !isset($_GET['id'])) Redirect::to('index.php?page=home');

$id = DataProcessor::sanitizeData($_GET['id']);
$type = DataProcessor::sanitizeData($_GET['type']);

if ($_SESSION['user']['role'] != 'teacher') {
    if ($type == "group" && !DataProcessor::registeredValue('group_member', [
        'user_id' => $_SESSION['user']['id'],
        'group_id' => $id
    ])) Redirect::to('index.php');
    if ($type == "user" && $_GET['id'] != $_SESSION['user']['id']) Redirect::to('/index.php');
}


$feedback = Feedback::getFeedback($_GET['type'], $_GET['id']);

// print_p($feedback);

if (empty($feedback)) {
    echo "Phew... You've not received any feedback yet.";
}

echo (isset($_SESSION['ERROR']['FEEDBACK_ERROR'])) ? $_SESSION['ERROR']['FEEDBACK_ERROR'] : '';
foreach ($feedback as $fb) {
    echo "
        <div class='feedback'>
            <p class='feedback-item' id='title'>{$fb['title']}</p>
            <p class='feedback-item' id='description'><pre>{$fb['description']}</pre></p>
            <p class='feedback-item' id='teacher'>{$fb['teacher_name']}</p>
        </div><br>
    ";
}
