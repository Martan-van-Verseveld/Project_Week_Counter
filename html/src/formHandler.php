<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/inc/class_autoloader.inc.php";

echo "<pre>";
print_r($_POST);
echo "</pre>";


if (isset($_POST['submit-login'])) {
    $loginHandler = new LoginHandler();
    $handleForm = $loginHandler->handleForm($_POST);
}

if (isset($_POST['submit-register'])) {
    $regHandler = new RegistrationHandler();
    $handleForm = $regHandler->handleForm($_POST);
}

if (isset($_POST['submit-group_create'])) {
    $regHandler = new GroupHandler();
    $handleForm = $regHandler->handleCreateForm($_POST);
}

if (isset($_POST['submit-group_update'])) {
    $regHandler = new GroupHandler();
    $handleForm = $regHandler->handleUpdateForm($_POST);
}


header("Location: {$_SERVER['HTTP_REFERER']}");
