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
            INSERT INTO `group_request` (user_id, group_id)
            VALUES (:user_id, :group_id);
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
}

Request::initialize();