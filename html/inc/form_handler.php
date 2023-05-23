<?php
# Написано Мартан ван Версевелд #


// return if post xor empty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) return;
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/classes.php");
session_start();


function formErrorHandler($msg) {
    $_SESSION["FORM_ERROR"] = $msg;
    // header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}


// Debug stuff
print_p($_POST);
print_h(getallheaders());

$GLOBALS['passwd_config'] = [
    'salt' => "4b58e936d051dd2ad039ef12d5c0174f",
    'pepper' => "1166148776b0476d7a3a60be63d31ae4",
    'encryption' => CRYPT_SHA256
];

// Connect to database
$conn = require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/db_config.php');
$crud = new CRUD('localhost', 3306, 'pw_counter', 'pw_counter', 'YU@aHb01j6[UKXlu');


// Registration
if (isset($_POST['submit_register'])) {
    // Setting variables
    $email = sanitizeInput($_POST['email']);
    $firstname = sanitizeInput($_POST['firstname']);
    $lastname = sanitizeInput($_POST['lastname']);
    $role = sanitizeInput($_POST['role']);
    $password = sanitizeInput($_POST['password']);
    $password_conf = sanitizeInput($_POST['password_conf']);

    // Checking for errors
    if (strlen($email) > 32 || strlen($firstname) > 8 || strlen($lastname) > 24 || strlen($role) > 12 || strlen($password) > 32 || strlen($password_conf) > 32) {
        formErrorHandler("Email, Firstname, Lastname, Role, Passwords are empty!");
    }
    if ($password != $password_conf) {
        formErrorHandler("Passwords don't match!");
    }
    

    // Checking for existing data
    $results = $crud->read('users', ['id', 'email'], [
        'email' => $email,
    ]);

    print_p($results);

    if (!empty($results)) {
        formErrorHandler("EMail is already registered!");
    }

    // Password hashing
    $password_db = password_hash($passwd_config['pepper'] . $password . $passwd_config['salt'], $passwd_config['encryption']);

    $insertId = $crud->create('users', [
        'email' => $email,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'role' => $role,
        'password' => $password_db
    ]);
    
    if ($insertId) {
        formErrorHandler("Successfully registered account!");
    } else {
        formErrorHandler("Failed to insert record. Please, try again later!");
    }
}

// Login
if (isset($_POST['submit_login'])) {
    // Setting variables
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Checking for errors
    if (empty($email) || empty($password)) {
        formErrorHandler("Email or Password empty!");
    }
    if (strlen($email) > 32 || strlen($password) > 32) {
        formErrorHandler("Email or password to long!");
    }

    // Checking for existing data
    $results_users = $crud->read('users', ['id', 'email', 'firstname', 'lastname', 'password', 'class', 'role'], [
        'email' => $email,
    ]);
    $crud->showQuery();

    print_p($results_users);

    // Checking for more errors
    if (empty($results_users)) {
        formErrorHandler("EMail is incorrect!");
    }
    if (!password_verify($passwd_config['pepper'] . $password . $passwd_config['salt'], $results_users[0]['password'])) {
        formErrorHandler("Password is incorrect!");
    }
    
    $results = $crud->customQuery("
        SELECT `users`.email, `users`.firstname, `users`.lastname, `users`.class, `users`.role, groups.name, groups.description, `group_members`.role AS group_role
        FROM `users`
        INNER JOIN `group_members` ON `group_members`.user_id = `users`.id
        INNER JOIN `groups` ON `groups`.id = `group_members`.group_id
        WHERE `users`.id = :user_id", 
        [
            'user_id' => $results_users[0]['id']
        ]
    );
    $crud->showQuery();

$_SESSION['USER_INFO'] = array(
    'email' => $results[0]['email'],
    'firstname' => $results[0]['firstname'],
    'lastname' => $results[0]['lastname'],
    'class' => $results[0]['class'],
    'role' => $results[0]['role']
);
$_SESSION['GROUP_INFO'] = array(
    'name' => $results[0]['name'],
    'role' => $results[0]['group_role'],
    'description' => $results[0]['description']
);


    print_p($_SESSION);

    formErrorHandler("Logged in successfully!");
}

// Close database connection
$crud = null; 
