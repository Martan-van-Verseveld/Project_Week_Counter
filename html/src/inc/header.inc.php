<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/media/images/favicon.png">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/libs/bootstrap/bootstrap_icons.css">
    <script src="https://kit.fontawesome.com/ffa3a463b6.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/functions.js"></script>
    <script type="text/javascript" src="/js/welcomeMsg.js" defer></script>
    <title><?= ucfirst($_GET['page']) ?></title>
</head>
<body>
<header>
    <nav class="topnav">
        <ul>
            <div id="logo">
                <a href="/index.php?page=home"></a>
            </div>
            <div class="centerContent">
                <li>
                    <a href="/index.php?page=scores" class="Hover">Scores</a>
                </li>
                <div class="line"></div>
                <?php if (!empty($_SESSION['user'])): ?>
                <li>
                    <a href="/index.php?page=<?= ($_SESSION['user']['role'] == 'teacher') ? 'db_teacher' : 'db_student'; ?>" class="Hover">Dashboard</a>
                </li>
                <!-- TEMP -->
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=inbox" class="Hover">Inbox</a>
                </li>
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=settings" class="Hover">Settings</a>
                </li>
                <!-- TEMP -->
                <div class="line"></div>
                <?php endif; ?>
                <li>
                    <a href="/index.php?page=groups" class="Hover">Groups</a>
                </li>
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=help" id="help" class="Hover">Help</a>
                </li>
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=about" class="Hover">About</a>
                </li>
                <!-- TEMP -->
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=users" class="Hover">Users</a>
                </li>
                <!-- TEMP -->
            </div>
            <?php if (empty($_SESSION['user'])): ?>
            <div class="user_opt">
                <li id="loginbtn">
                    <a href="/index.php?page=login" id="login" class="Hover">Login</a>
                </li>
                <li>
                    <a href="/index.php?page=register" id="register">Register</a>
                </li>
            </div>
            <?php else: ?>
            <div class="user_opt">
                <li>
                    <a href="/index.php?page=logout" id="register">Logout</a>
                </li>
            </div>
            <?php endif; ?>
        </ul>
    </nav>
</header>
