<?php

if (empty($_SESSION['user']) || !isset($_SESSION['user'])) Redirect::to('/index.php?page=home');


$teacher = $_SESSION['user']['role'] == 'teacher';

$themes = Theme::getThemes($teacher);

if ($teacher) {
    echo "<a href='index.php?page=theme_create'>Create new Project Theme</a>";
}

// print_p($themes);

foreach ($themes as $theme) {
    echo "
        <br>
        <div class='theme'>
            <a href='/index.php?page=theme&id={$theme['id']}'>View this theme</a>
            <p class='theme-item' id='title'>{$theme['title']}</p>
            <p class='theme-item' id='registered'>{$theme['register_count']} / {$theme['max']}</p>
        </div>
    ";
}
