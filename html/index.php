<?php
# Написано Мартан ван Версевелд #
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_GET['page']) || empty($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/classes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");


$GLOBALS['__PAGE__'] = new page(htmlspecialchars($_REQUEST['page']));

$__PAGE__->load_full();

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/db_config.php");

// print_p($_SESSION);