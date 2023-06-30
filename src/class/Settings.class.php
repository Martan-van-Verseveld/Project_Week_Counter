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

    public static function updateSetting($userId, $setting, $value)
    {
        $userId = DataProcessor::sanitizeData($userId);
        $setting = DataProcessor::sanitizeData($setting);
        $value = DataProcessor::sanitizeData($value);

        // Prepare the SQL query
        $query = "
            UPDATE `settings`
            SET $setting = :value
            WHERE user_id = :user_id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':user_id' => $userId,
            ':value' => $value
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function updateSettings($userId, $settings) {
        $userId = DataProcessor::sanitizeData($userId);
        $settings = DataProcessor::sanitizeData($settings);
    
        foreach ($settings as $key => $value) {
            try {
                self::updateSetting($userId, $key, $value);
            } catch (PDOException $e) {
                Session::put('PDOException', $e);
            }
        }
    
        return true;
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

    public static function getSetting($userId, $setting, $value) 
    {
        $userId = DataProcessor::sanitizeData($userId);
        $setting = DataProcessor::sanitizeData($setting);
        $value = DataProcessor::sanitizeData($value);

        // Prepare the SQL query
        $query = "
            SELECT *
            FROM `settings`
            WHERE user_id = :userId AND $setting = :value;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':userId' => $userId,
            ':value' => $value
        ]);

        $results = $sto->fetch(PDO::FETCH_ASSOC);
        return !empty($results);
    }

    public static function isChatable($userId) 
    {
        return DataProcessor::registeredValue('settings', [
            'user_id' => $userId,
            'chat' => 1
        ]);
    }

    public static function isInviteable($userId)
    {
        return DataProcessor::registeredValue('settings', [
            'user_id' => $userId,
            'invite' => 1
        ]);
    }
}

Settings::initialize();
