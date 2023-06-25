<?php

class Group 
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function create($data) 
    {
        $data = DataProcessor::sanitizeData($data);

        // Prepare the SQL query
        $query = "
            INSERT INTO `group_info` (name, description)
            VALUES (:name, :description);
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':name' => $data['name'],
            ':description' => $data['description']
        ]);

        $lastId = self::$pdo->lastInsertId();

        // Check insert success
        $insert = $sto->rowCount();
        if ($insert <= 0) return false;

        $groupId = self::$pdo->lastInsertId();
        Score::create($groupId);

        Member::create(([
            'user_id' => $data['user_id'],
            'group_id' => $groupId
        ]));

        $ownership = Member::updateOwnership([
            'user_id' => $data['user_id'],
            'group_id' => $groupId
        ]);

        return $lastId;        
    }

    public static function update($groupId, $data) 
    {        
        $groupId = DataProcessor::sanitizeData($groupId);
        $data = DataProcessor::sanitizeData($data);

        foreach ($data as $key => $value) {
            // Prepare the SQL query
            $query = "
                UPDATE `group_info`
                SET $key = :value
                WHERE id = :group_id;
            ";

            try {
                // Execute statement
                $sto = self::$pdo->prepare($query);
                $sto->execute([
                    ':group_id' => $groupId,
                    ':value' => $value
                ]);
            } catch (PDOException $e) {
                Session::pdoDebug($e);
            }
        }

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function getGroups()
    {
        // Prepare the SQL query
        $query = "
            SELECT `group_info`.*, COUNT(`group_member`.id) as member_count
            FROM `group_info`
            INNER JOIN `group_member` ON `group_member`.group_id = `group_info`.id
            GROUP BY `group_info`.id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute();

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($fetch as $result) {
            $results[$result['id']] = $result;
        }

        return $results;
    }

    public static function getGroup($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);

        // Prepare the SQL query
        $query = "
            SELECT *
            FROM `group_info`
            WHERE id = :groupId;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':groupId' => $groupId
        ]);

        $fetch = $sto->fetch(PDO::FETCH_ASSOC);

        return $fetch;
    }

    public static function getUserGroup($userId)
    {
        $groupId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $query = "
            SELECT `group_info`.*, MAX(`group_member`.role) as 'member_role', COUNT(`group_member`.id) as 'member_count'
            FROM `group_member`
            INNER JOIN `group_info` ON `group_info`.id = `group_member`.group_id
            WHERE `group_member`.user_id = :userId
            GROUP BY `group_info`.id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':userId' => $userId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetch(PDO::FETCH_ASSOC);

        return $fetch;
    }

    public static function getGroupMembers($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);

        $query = "
            SELECT `user`.*, `group_member`.role as group_role
            FROM `group_member`
            INNER JOIN `user` ON `user`.id = `group_member`.user_id
            WHERE `group_member`.group_id = :groupId;
        ";

        try {
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':groupId' => $groupId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($fetch as $result) {
            $results[$result['id']] = $result;
        }

        return $results;
    }

    public static function getGroupRequests($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);

        $query = "
            SELECT *
            FROM `group_request`
            WHERE group_id = :groupId AND type = 'request';
        ";

        try {
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':groupId' => $groupId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($fetch as $result) {
            $results[$result['id']] = $result;
        }

        return $results;
    }

    public static function getGroupInfo($groupId) 
    {
        $groupId = DataProcessor::sanitizeData($groupId);
        
        $results = [
            "Group" => self::getGroup($groupId),
            "Users" => self::getGroupMembers($groupId),
            "Requests" => self::getGroupRequests($groupId)
        ];
        return $results;
    }

    public static function getMemberCount($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);

        $query = "
            SELECT COUNT(id) as count
            FROM `group_member`
            WHERE group_id = :group_id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':group_id' => $groupId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetch(PDO::FETCH_ASSOC)['count'];
        return $fetch;
    }

    public static function delete($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);
        $members = self::getGroupMembers($groupId);

        // Prepare the SQL query
        $query = "
            DELETE FROM `group_info`
            WHERE id = :group_id
            LIMIT 1;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':group_id' => $groupId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        foreach ($members as $member) {
            Member::delete($member['id'], $groupId);
        }

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function isRegistered($groupName, $groupId) 
    {
        $groupName = DataProcessor::sanitizeData($groupName);
        $groupId = DataProcessor::sanitizeData($groupId);

        $query = "
            SELECT * 
            FROM `group_info`
            WHERE name = :group_name AND NOT id = :group_id ;
        ";

        // Fetch the result
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            'group_name' => $groupName,
            'group_id' => $groupId
        ]);
        
        $results = $sto->rowCount();
        return ($results > 0);
    }
}

Group::initialize();
