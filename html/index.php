<?php

error_reporting(E_ALL);
require_once(__DIR__ . "/inc/classes.php");

if (!isset($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}


$GLOBALS['__PAGE__'] = new page($_REQUEST['page']);

$__PAGE__->load_full();
