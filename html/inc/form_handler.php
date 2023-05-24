<?php
# Написано Мартан ван Версевелд #

// Load classes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) return;
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/functions.php');// Autoload
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class); // Convert namespace separators to directory separators
    $file = $_SERVER['DOCUMENT_ROOT'] . "/inc/classes/" . $class . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});


// Debug stuff
error_reporting(E_ALL);
ini_set('display_errors', 1);
print_p($_POST);
print_h(getallheaders());

// Connect crud
$crud = new CRUD('localhost', 3306, 'pw_counter', 'pw_counter', 'YU@aHb01j6[UKXlu');


// Registration
if (isset($_POST['submit_register'])) {
    $registrationForm = new RegistrationForm($crud, $_POST);
    $registrationForm->processForm();    
}

// Login
if (isset($_POST['submit_login'])) {
    $loginForm = new LoginForm($crud, $_POST);
    $loginForm->processForm();  
}
