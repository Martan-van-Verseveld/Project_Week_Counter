<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    $dbConfig = new DBConfig('localhost', 3306, 'pw_counter', 'pw_counter', 'YU@aHb01j6[UKXlu');
    $groupInfo = new GroupInfo($dbConfig->getConnection() );


    $group_id = intval(sanitizeInput($_GET['group_id']));
    $group_info = $groupInfo->getGroupInfo($group_id);
    $group_members = $groupInfo->getGroupMembers($group_id);

    echo "
        <form action='' method='GET'>
            <input type='hidden' name='page' value='group_test'>
            <input type='number' name='group_id'>
            <input type='number' name='user_id'>
            <input type='submit'>
        </form>
    ";

    print_p([
        'group_info' => $group_info, 
        'group_members' => $group_members
    ]);

    $groups = $groupInfo->getGroups();
    print_p($groups);

    $user_id = intval(sanitizeInput($_GET['user_id']));
    $group_user = $groupInfo->getUserGroup($user_id);
    print_p($group_user);

