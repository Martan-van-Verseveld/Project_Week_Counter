<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    $dbConfig = new DBConfig('localhost', 3306, 'pw_counter', 'pw_counter', 'YU@aHb01j6[UKXlu');
    $userInfo = new UserInfo($dbConfig->getConnection());

    
    $user_id = intval(sanitizeInput($_GET['user_id']));
    $user_info = $userInfo->getUserInfo($user_id);
    $users = $userInfo->getUsers();


    echo "
        <br><form action='' method='GET'>
            <input type='hidden' name='page' value='user_test'>
            <select name='user_id'>
    ";
    foreach ($users as $user) {
        if ($user['id'] == $user_id) {
            echo "<option selected value='". $user['id'] ."'>". $user['id'] ." (". $user['email'] .")</option>";
        } else {
            echo "<option value='". $user['id'] ."'>". $user['id'] ." (". $user['email'] .")</option>";
        }
    }
    echo "
            </select>
            <input type='submit'>
        </form><br>
    ";

    print_h($user_info);
    echo "<br>";
    print_p($users);