<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');
$classId = DataProcessor::sanitizeData($_GET['id']);
if ($_SESSION['user']['role'] != 'teacher') Redirect::to('/index.php');


$class = SchoolClass::getClass($classId);

echo "
    <form action='src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='class-edit'>
        <input type='hidden' name='class_id' value='{$class['id']}'>
        <label for='name'>Edit your class!</label><br>
        <input type='text' name='name' id='name' placeholder='Class name here...' value='{$class['name']}' maxlength='9'><br>
        <input type='submit' value='Update your class'>
    </form>
    <a href='index.php?page=class&id={$class['id']}'>Back</a>
";
