<main>
    <?php

        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        $crud = new CRUD('localhost', 3306, 'pw_counter', 'pw_counter', 'YU@aHb01j6[UKXlu');

        $groupIDs = $crud->read('groups', '*');
        foreach ($groupIDs as $groupID) {
            $groupID = $groupID['id'];
            $users = $crud->customQuery("
                SELECT users.id, users.firstname, users.lastname, gm.role
                FROM users
                INNER JOIN group_members gm ON gm.user_id = users.id
                WHERE gm.group_id = :group_id",
                [
                    'group_id' => $groupID
                ]
            );
        
            $results[$groupID] = $users;
        }

        // Print the results
        foreach ($results as $groupID => $users) {
            echo "Group ID: $groupID<br><br>";
            foreach ($users as $user) {
                print_h($user);
                echo "<br/>";
            }
            echo "<br>";
        }

    ?>
</main>