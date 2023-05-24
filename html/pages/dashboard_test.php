<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    $crud = new CRUD('localhost', 3306, 'pw_counter', 'pw_counter', 'YU@aHb01j6[UKXlu');     
    
    // foreach ($groupIDs as $groupID) {
    //     $groupID = $groupID['id'];
    //     $user = $crud->customQuery("
    //         SELECT `user`.id, `user`.email, `user`.firstname, `user`.lastname, `user`.class, `user`.role, 
    //             `group_member`.user_id, `group_member`.group_id
    //         FROM `user`
    //         INNER JOIN `group_member` ON `group_member`.user_id = `user`.id;
    //         ", [
    //             'group_id' => $groupID
    //         ]
    //     );
    
    //     $results[$groupID] = $user;
    // }

    $users = $crud->customQuery("
        SELECT `user`.id, `user`.email, `user`.firstname, `user`.lastname, `user`.class, `user`.role
        FROM `user`
    ");
    $crud->showQuery();

    $user_info = $crud->read('user', ['id', 'email', 'firstname', 'lastname', 'class', 'role'], [
        'email' => $_SESSION['USER_INFO']['email']
    ]);
    print_p($user_info);

    $group_info = $crud->customQuery("
        SELECT `group_info`.name, `group_info`.description
        FROM `group_member`
        INNER JOIN `group_info` ON `group_info`.id = :group_id;
        ", [
            'group_id' => sanitizeInput($_GET['group_id'])
        ]
    );
    $crud->showQuery();
    print_h($group_info[0]);

    $group_members = $crud->customQuery("
        SELECT `user`.email, `user`.firstname, `user`.lastname, `user`.role as user_role,
                `group_member`.role as group_role
        FROM `group_member`
        INNER JOIN `user` ON `user`.id = `group_member`.user_id
        WHERE `group_member`.group_id = :group_id;
        ", [
            'group_id' => $group_info[0]['group_id']
        ]
    );
    $crud->showQuery();


    echo "<h2>Group Info:</h2>";
    print_h($group_info[0]);
    echo "<br/>";

    echo "<h2>Group Members:</h2>";
    foreach ($group_members as $group_member) {
        print_h($group_member);
        echo "<br/>";
    }

    foreach ($group_members as $member) {
        if ($member['email'] == $_SESSION['USER_INFO']['email'] && $member['group_role'] == 'owner' || $user_info[0]['role'] == 'teacher') {
            echo "True";
        }
    }
