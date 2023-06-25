<?php

class Member
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
            INSERT INTO `group_member` (user_id, group_id)
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

    public static function delete($userId, $groupId) 
    {
        $userId = DataProcessor::sanitizeData($userId);
        $groupId = DataProcessor::sanitizeData($groupId);

        // Prepare the SQL query
        $query = "
            DELETE FROM `group_member`
            WHERE user_id = :user_id AND group_id = :group_id
            LIMIT 1;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $userId,
                ':group_id' => $groupId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function updateOwnership($data) 
    {
        $data = DataProcessor::sanitizeData($data);

        // Prepare the SQL query
        $query = "
            UPDATE `group_member`
            SET role = 'member'
            WHERE role = 'owner' AND group_id = :group_id;

            UPDATE `group_member`
            SET role = 'owner'
            WHERE user_id = :user_id AND group_id = :group_id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':user_id' => $data['user_id'],
            ':group_id' => $data['group_id'],
            ':role' => 'owner'
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function isMember($userId, $groupId) 
    {
        return DataProcessor::registeredValue('group_member', [
            'user_id' => $userId,
            'group_id' => $groupId
        ]);
    }

    public static function isOwner($userId, $groupId) 
    {
        return DataProcessor::registeredValue('group_member', [
            'user_id' => $userId,
            'group_id' => $groupId,
            'role' => 'owner'
        ]);
    }
}

Member::initialize();