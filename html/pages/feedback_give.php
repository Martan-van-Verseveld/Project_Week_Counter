<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');
if (empty($_GET['type']) || !isset($_GET['id'])) Redirect::to('/index.php?page=home');
if ($_SESSION['user']['role'] != 'teacher') Redirect::to('/index.php?page=home');

$id = DataProcessor::sanitizeData($_GET['id']);
$type = DataProcessor::sanitizeData($_GET['type']);

echo "
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='feedback-give'>
        <input type='hidden' name='id' value='{$_GET['id']}'>
        <input type='hidden' name='type' value='{$_GET['type']}'>
        <input type='text' name='title' placeholder='Feedback title here...' maxlength='32'>
        <textarea name='description' placeholder='Feedback description here...'></textarea>
        <input type='submit' value='Submit feedback'>
    </form>
";
