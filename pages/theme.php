<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');
if (!isset($_GET['id'])) Redirect::to('/index.php?page=home');

$themeId = DataProcessor::sanitizeData($_GET['id']);
$theme = Theme::getTheme($themeId);

if ($_SESSION['user']['role'] != 'teacher') {
    if ($theme['hidden'] && !DataProcessor::registeredValue('group_theme', [
        'theme_id' => $themeId,
        'group_id' => Group::getUserGroup($_SESSION['user']['id'])['id']
    ])) Redirect::to('/index.php?page=themes');
}

$userGroup = Group::getUserGroup($_SESSION['user']['id']);

// echo "<span id='error'>{$_SESSION['ERROR']['THEME_ERROR']}</span>";

if ($_SESSION['user']['role'] == 'teacher') {
    echo "<a href='/index.php?page=theme_edit&id={$theme['id']}'>Edit this theme</a>";
} else 
if (!empty($userGroup) 
    && Member::isOwner($_SESSION['user']['id'], $userGroup['id']) 
    && !DataProcessor::registeredValue('group_theme', [
        'group_id' => $userGroup['id'],
        'theme_id' => $themeId
    ]) 
    && $theme['register_count'] < $theme['max']
) {
    echo "
        <form action='src/inc/formHandler.inc.php' method='POST'>
            <input type='hidden' name='action' value='theme-register'>
            <input type='hidden' name='group_id' value='{$userGroup['id']}'>
            <input type='hidden' name='theme_id' value='{$themeId}'>
            <input type='submit' value='Register this theme'>
        </form>
    ";
} else 
if (!empty($userGroup) 
    && Member::isOwner($_SESSION['user']['id'], $userGroup['id']) 
    && DataProcessor::registeredValue('group_theme', [
        'group_id' => $userGroup['id'],
        'theme_id' => $themeId
    ]) 
) {
    echo "
        <form action='src/inc/formHandler.inc.php' method='POST'>
            <input type='hidden' name='action' value='theme-leave'>
            <input type='hidden' name='group_id' value='{$userGroup['id']}'>
            <input type='hidden' name='theme_id' value='{$themeId}'>
            <input type='submit' value='Leave this theme'>
        </form>
    ";
}

echo "
    <div class='theme'>
        <p class='theme-item' id='title'>{$theme['title']}</p>
        <p class='theme-item' id='description'><pre>{$theme['description']}</pre></p>
    </div>
";

// print_p($theme);
