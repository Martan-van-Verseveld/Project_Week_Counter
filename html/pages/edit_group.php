<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');
$groupId = DataProcessor::sanitizeData($_GET['id']);

if (!DataProcessor::registeredValue('group_member', [
    'user_id' => $_SESSION['user']['id'],
    'group_id' => $groupId,
    'role' => 'owner'
]) && $_SESSION['user']['role'] != 'teacher') Redirect::to('/index.php');


$group = Group::getGroup($groupId);

echo "
    <form action='/src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='group-edit'>
        <input type='hidden' name='group_id' value='{$group['id']}'>
        <label for='name'>Edit your group!</label><br>
        <input type='text' name='name' id='name' placeholder='Group name here...' value='{$group['name']}'><br>
        <textarea name='description' id='description' cols='30' rows='10' placeholder='Group description here...'>{$group['description']}</textarea><br>
        <input type='submit' value='Update your group'>
    </form>
    <a href='/index.php?page=group&id={$group['id']}'>Back</a>
";
