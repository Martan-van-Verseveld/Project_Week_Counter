<?php

// return if post but empty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) return;
require($_SERVER['DOCUMENT_ROOT'] . '/inc/functions.php');

// Debug stuff
print_p($_POST);
print_h(getallheaders());

$GLOBALS['passwd_config'] = [
    'salt' => "4b58e936d051dd2ad039ef12d5c0174f",
    'pepper' => "1166148776b0476d7a3a60be63d31ae4"
];

// Registration
if (isset($_POST['submit_register'])) {
    // Setting variables
    $email = htmlspecialchars(str_replace(' ', '', $_POST['email']));
    $firstname = htmlspecialchars(str_replace(' ', '', $_POST['firstname']));
    $lastname = htmlspecialchars(str_replace(' ', '', $_POST['lastname']));
    $role = htmlspecialchars(str_replace(' ', '', $_POST['role']));
    $password = htmlspecialchars(str_replace(' ', '', $_POST['password']));
    $password_conf = htmlspecialchars(str_replace(' ', '', $_POST['password_conf']));

    // Checking for errors
    if (empty($email) || empty($firstname) || empty($lastname) || empty($role) || empty($password) || empty($password_conf)) {
        echo "Email, Firstname, Lastname, Role, Passwords are empty!";
        return;
    }
    if ($password != $password_conf) {
        echo "Passwords don't match!";
        return;
    }

    // Connect to db when it passes all checks
    $conn = require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/db_config.php');
    echo ($conn) ? 'Success!' : 'Error!';

    // Checking for existing data
    $sql = "
    SELECT `email`, `firstname`, `lastname`
    FROM `users`
    WHERE `email` = :email;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([ 
        ':email' => $email
    ]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_p($results);

    if (!empty($results)) {
        echo "EMail is already registered!";
        return;
    }

    // Password hashing
    $password_db = password_hash($passwd_config['pepper'] . $password . $passwd_config['salt'], CRYPT_SHA256);
    
    // Sql
    $sql = "
    INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `class`, `role`) 
    VALUES (NULL, :email, :firstname, :lastname, :password, NULL, :role);
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':role' => $role,
        ':password' => $password_db
    ]);

    // Removing conn
    $conn = null;
}

// Registration
if (isset($_POST['submit_login'])) {
    // Setting variables
    $email = htmlspecialchars(str_replace(' ', '', $_POST['email']));
    $password = htmlspecialchars(str_replace(' ', '', $_POST['password']));

    // Checking for errors
    if (empty($email) || empty($password)) {
        echo "Email or Password empty!";
        return;
    }

    // Connect to db when it passes all checks
    $conn = require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/db_config.php');
    echo ($conn) ? 'Success!' : 'Error!';

    // Checking for existing data
    $sql = "
    SELECT `email`, `password`
    FROM `users`
    WHERE `email` = :email;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([ 
        ':email' => $email
    ]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_p($results);

    if (empty($results)) {
        echo "EMail is not registered!";
        return;
    }
    if (!password_verify($passwd_config['pepper'] . $password . $passwd_config['salt'], $results[0]['password'])) {
        echo "Password is wrong!";
        return;
    }

    // Password hashing
    $password_db = password_hash($passwd_config['pepper'] . $password . $passwd_config['salt'], CRYPT_SHA256);
    
    echo "Logged in!";

    // Removing conn
    $conn = null;
}

