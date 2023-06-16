<?php

class Group 
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function create($data): bool 
    {
        $data = DataProcessor::sanitizeData($data);

        // Hash password
        $hashed_passwd = password_hash(PASS_PEPPER . $data['password'] . PASS_SALT, PASS_ENC);

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

    public static function getGroupMembers($groupId)
    {
        $groupId = DataProcessor::sanitizeData($groupId);

        $query = "
            SELECT `user`.*, `group_member`.role as group_role
            FROM `group_member`
            INNER JOIN `user` ON `user`.id = `group_member`.user_id
            WHERE `group_member`.group_id = :groupId;
        ";

        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':groupId' => $groupId
        ]);

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
            WHERE group_id = :groupId;
        ";

        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':groupId' => $groupId
        ]);

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
}

Group::initialize();
