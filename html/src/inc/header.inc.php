<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/t_style.css">
    <link rel="stylesheet" href="/libs/bootstrap/bootstrap_icons.css">
    <script src="/js/functions.js"></script>
    <title><?= ucfirst($_GET['page']) ?></title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php

                    $pages = array_diff(scandir("{$_SERVER['DOCUMENT_ROOT']}/pages"), ['.', '..']);

                    foreach ($pages as $page) {
                        $page = basename($page, '.php');
                        echo "<li". (($page == $_GET['page']) ? " id='active'" : "") ."><a href='/index.php?page=$page'>". ucfirst($page) ."</a></li>";
                    }

                ?>
            </ul>
        </nav>
        <!-- <h1><a href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/index.php?page=home' ?>"><?= ucfirst($_GET['page']) ?> page</a></h1> -->
    </header>