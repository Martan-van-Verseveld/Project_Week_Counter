<?php

class Settings
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function create($userId): bool 
    {
        $userId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $query = "
            INSERT INTO `settings` (user_id)
            VALUES (:user_id);
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

    public static function updateSetting($userId, $settings)
    {

    }

    public static function getSettings($userId) 
    {
        $userId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $query = "
            SELECT *
            FROM `settings`
            WHERE user_id = :userId;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':userId' => $userId
        ]);

        $results = $sto->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
}

Settings::initialize();
