<?php

class SchoolClassMember
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
            INSERT INTO `class_member` (user_id, class_id)
            VALUES (:user_id, :class_id);
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $data['user_id'],
                ':class_id' => $data['class_id']
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }
    
    public static function delete($data): bool 
    {
        $data = DataProcessor::sanitizeData($data);

        // Prepare the SQL query
        $query = "
            DELETE FROM `class_member`
            WHERE class_id = :class_id AND user_id = :user_id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $data['user_id'],
                ':class_id' => $data['class_id']
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        // Check insert success
        $delete = $sto->rowCount();
        return ($delete > 0);
    }
}

SchoolClassMember::initialize();


