<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/libs/fontawesome/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/media/images/favicon.png">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/libs/bootstrap/bootstrap_icons.css">
    <!-- <script src="https://kit.fontawesome.com/ffa3a463b6.js" crossorigin="anonymous"></script> -->
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
                    <a href="/index.php?page=scores" class="Hover"><i class="fa-solid fa-star"></i> Scores</a>
                </li>
                <div class="line"></div>
                <?php if (!empty($_SESSION['user'])): ?>
                <li>
                    <a href="/index.php?page=profile&id=<?= $_SESSION['user']['id'] ?>" class="Hover"><i class="fa-solid fa-user"></i> Profile</a>
                </li>
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=themes" class="Hover"><i class="fa-solid fa-file"></i> Themes</a>
                </li>
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=chat" class="Hover"><i class="fa-solid fa-comment"></i> Chat</a>
                </li>
                <div class="line"></div>
                <!-- <li>
                    <a href="/index.php?page=<?= ($_SESSION['user']['role'] == 'teacher') ? 'db_teacher' : 'db_student'; ?>" class="Hover"><i class="fa-solid fa-clipboard"></i> Dashboard</a>
                </li>
                <div class="line"></div> -->
                <?php if (DataProcessor::registeredValue('class_member', ['user_id' => $_SESSION['user']['id']])): ?>
                <li>
                    <a href="/index.php?page=class&id=<?= SchoolClass::getUserClass($_SESSION['user']['id'])['id'] ?>" class="Hover">Class</a>
                </li>
                <div class="line"></div>
                <?php endif; ?>
                <?php if ($_SESSION['user']['role'] == 'teacher'): ?>
                <li>
                    <a href="/index.php?page=classes" class="Hover">Classes</a>
                </li>
                <div class="line"></div>
                <?php endif; ?>
                <?php if (DataProcessor::registeredValue('group_member', ['user_id' => $_SESSION['user']['id']])): ?>
                <li>
                    <a href="/index.php?page=group&id=<?= Group::getUserGroup($_SESSION['user']['id'])['id'] ?>" class="Hover"><i class="fa-solid fa-users-between-lines"></i> Group</a>
                </li>
                <div class="line"></div>
                <?php endif; ?>
                <li>
                    <a href="/index.php?page=groups" class="Hover"><i class="fa-solid fa-address-book"></i> Groups</a>
                </li>
                <div class="line"></div>
                <?php endif; ?>
                <li>
                    <a href="/index.php?page=help" id="help" class="Hover"><i class="fa-solid fa-circle-question"></i> Help</a>
                </li>
                <div class="line"></div>
                <li>
                    <a href="/index.php?page=about" class="Hover"><i class="fa-solid fa-globe"></i> About</a>
                </li>
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
                    <a href="/index.php?page=inbox" id="inbox" class="Hover" style='margin-right: 10px;'><i class="fa-regular fa-bell"></i></a>
                </li>
                <li>
                    <a href="/index.php?page=settings" id="settings" class="Hover" style='margin-right: 10px;'><i class="fas fa-cog"></i></a>
                </li>
                <li>
                    <a href="/index.php?page=logout" id="register">Logout</a>
                </li>
            </div>
            <?php endif; ?>
        </ul>
    </nav>
</header>
