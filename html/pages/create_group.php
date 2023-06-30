<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

if (DataProcessor::registeredValue('group_member', [
    'user_id' => $_SESSION['user']['id']
])) Redirect::to();

?>

<form action="src/inc/formHandler.inc.php" method='POST'>
    <input type="hidden" name='action' value='group-create'>
    <label for="name">Create your group!</label><br>
    <input type="text" name="name" id="name" placeholder="Group name here..."><br>
    <textarea name="description" id="description" cols="30" rows="10" placeholder="Group description here..."></textarea><br>
    <input type="submit" value="Create your group now">
</form>