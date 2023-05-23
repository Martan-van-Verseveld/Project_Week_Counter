<?php

if (!isset($_GET['page']) || empty($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}

require_once(__DIR__ . "/inc/classes.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");


$GLOBALS['__PAGE__'] = new page(htmlspecialchars($_REQUEST['page']));

$__PAGE__->load_full();

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/db_config.php");