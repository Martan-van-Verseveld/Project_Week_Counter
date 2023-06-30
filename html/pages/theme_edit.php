<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');

if ($_SESSION['user']['role'] != 'teacher' || !DataProcessor::registeredValue('theme', ['id' => $_GET['id']])) Redirect::to('/index.php?page=themes');

$themeId = DataProcessor::sanitizeData($_GET['id']);
$theme = Theme::getTheme($themeId);

echo "
    <form action='src/inc/formHandler.inc.php' method='POST'>
        <input type='hidden' name='action' value='theme-edit'>
        <input type='hidden' name='theme_id' value='{$themeId}'>

        <label for='title'>Title: </label>
        <br><input type='text' name='title' id='title' value='{$theme['title']}'>
        <br>
        <br><label for='description'>Description: </label>
        <br><textarea name='description' id='description'>{$theme['description']}</textarea>
        <br>
        <br><p id='register_count'>Spots in use: {$theme['register_count']} / {$theme['max']}</p>
        <label for='max'>Max spots: </label>
        <br><input type='number' name='max' id='max' value='{$theme['max']}'>
        <br>
        <br><label for='active'>Active: </label>
        <input type='hidden' name='active' value='0'>
        <input type='checkbox' name='active' id='active' value='1'". ($theme['active'] ? 'checked' : '') .">
        <br>
        <br><label for='hidden'>Hidden: </label>
        <input type='hidden' name='hidden' value='0'>
        <input type='checkbox' name='hidden' id='hidden' value='1'". ($theme['hidden'] ? 'checked' : '') .">
        <br>
        <br><input type='submit' value='Update theme'>
    </form>
";

