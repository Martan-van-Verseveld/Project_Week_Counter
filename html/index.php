<?php
# Написано Мартан ван Версевелд #
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<pre>';

print_r($_SERVER);

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/inc/class_autoloader.inc.php";


if (!isset($_GET['page']) || empty($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/src/inc/functions.inc.php");

$pageRenderer = new PageRenderer("/pages");
$pageRenderer->renderPage(sanitizeInput($_GET['page']));

$_SESSION['ERROR'] = null;
