<?php

class Feedback 
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function create($type, $data) 
    {
        $type = DataProcessor::sanitizeData($type);
        $data = DataProcessor::sanitizeData($data);

        // Prepare the SQL query
        $query = "
            INSERT INTO `${type}_feedback` (${type}_id, teacher_id, title, description)
            VALUES (:type_id, :teacher_id, :title, :description);
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ":type_id" => $data["id"],
            ':teacher_id' => $data['teacher_id'],
            ":title" => $data['title'],
            ':description' => $data['description']
        ]);

        $lastId = self::$pdo->lastInsertId();

        // Check insert success
        $insert = $sto->rowCount();
        if ($insert <= 0) return false;

        return $lastId;        
    }

    public static function getFeedback($type, $id)
    {
        $type = DataProcessor::sanitizeData($type);
        $id = DataProcessor::sanitizeData($id);

        $query = "
            SELECT `${type}_feedback`.*, 
                CONCAT(`user`.firstname, ' ', `user`.lastname) AS teacher_name 
            FROM `${type}_feedback`
            INNER JOIN `user` ON `user`.id = `${type}_feedback`.teacher_id
            WHERE ${type}_id = :type_id;
        ";

        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':type_id' => $id
        ]);

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }
}

Feedback::initialize();
