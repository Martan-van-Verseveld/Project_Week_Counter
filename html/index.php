<?php
# Написано Мартан ван Версевелд #
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class); // Convert namespace separators to directory separators
    $file = $_SERVER['DOCUMENT_ROOT'] . "/inc/classes/" . $class . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});


if (!isset($_GET['page']) || empty($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");


$GLOBALS['__PAGE__'] = new page(htmlspecialchars($_REQUEST['page']));

$__PAGE__->load();

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/db_config.php");

echo "<div style='background: #99999966; padding: 1rem;'>";
print_p($_SESSION);
echo "</div>";