<?php

class Request
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

        // Prepare the SQL query
        $query = "
            INSERT INTO `group_request` (user_id, group_id, type)
            VALUES (:user_id, :group_id, :type);
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':user_id' => $data['user_id'],
            ':group_id' => $data['group_id'],
            ':type' => $data['type']
        ]);

        // Check insert success
        // $insert = $sto->rowCount();
        return self::$pdo->lastInsertId();
    }

    public static function remove($data) 
    {
        $data = DataProcessor::sanitizeData($data);

        // Prepare the SQL query
        $query = "
            DELETE FROM `group_request`
            WHERE user_id = :user_id AND group_id = :group_id
            LIMIT 1;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':user_id' => $data['user_id'],
            ':group_id' => $data['group_id']
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function accept($data) 
    {
        $data = DataProcessor::sanitizeData($data);

        $delete = self::remove($data);

        $insert = Member::create($data);
        return ($insert > 0);
    }

    public static function getInvites($userId)
    {
        $userId = DataProcessor::sanitizeData($userId);

        $query = "
            SELECT `group_request`.*, `group_info`.name
            FROM `group_request`
            INNER JOIN `group_info` ON `group_info`.id = `group_request`.group_id
            WHERE user_id = :userId AND type = :type;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':userId' => $userId,
            ':type' => 'invite'
        ]);

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function acceptInvite($userId, $groupId)
    {
        $userId = DataProcessor::sanitizeData($userId);
        $groupId = DataProcessor::sanitizeData($groupId);

        Member::create([
            'user_id' => $userId,
            'group_id' => $groupId
        ]);

        // Delete other requests
        $query = "
            DELETE FROM `group_request`
            WHERE user_id = :user_id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':user_id' => $userId
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function declineInvite($userId, $groupId)
    {
        $userId = DataProcessor::sanitizeData($userId);
        $groupId = DataProcessor::sanitizeData($groupId);

        // Delete other requests
        $query = "
            DELETE FROM `group_request`
            WHERE user_id = :user_id AND group_id = :group_id
            LIMIT 1;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            'user_id' => $userId,
            'group_id' => $groupId
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }
}

Request::initialize();