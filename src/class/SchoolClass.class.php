<?php

class SchoolClass
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
            INSERT INTO `class` (name)
            VALUES (:name);
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':name' => $data['name']
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $lastId = self::$pdo->lastInsertId();

        // Check insert success
        $insert = $sto->rowCount();
        if ($insert <= 0) return false;

        $classId = self::$pdo->lastInsertId();
        Score::create($classId);

        SchoolClassMember::create(([
            'class_id' => $classId,
            'user_id' => $data['user_id'],
            'role' => 'teacher'
        ]));

        return $lastId;        
    }

    public static function getUserClass($userId)
    {
        $userId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $query = "
            SELECT `class`.*, MAX(`class_member`.role) as 'member_role', COUNT(`class_member`.id) as 'member_count'
            FROM `class_member`
            INNER JOIN `class` ON `class`.id = `class_member`.class_id
            WHERE `class_member`.user_id = :userId
            GROUP BY `class`.id;
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

    public static function getClasses()
    {
        // Prepare the SQL query
        $query = "
            SELECT `class`.*, COUNT(`class_member`.id) as member_count
            FROM `class`
            INNER JOIN `class_member` ON `class_member`.class_id = `class`.id
            GROUP BY `class`.id;
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

    public static function getClass($classId)
    {
        $classId = DataProcessor::sanitizeData($classId);

        // Prepare the SQL query
        $query = "
            SELECT `class`.*, COUNT(`class_member`.id) as member_count, (
                    SELECT CONCAT(`user`.firstname, ' ', `user`.lastname) AS name
                    FROM `user`
                    INNER JOIN `class_member` ON `class_member`.user_id = `user`.id
                    WHERE `class_member`.role = 'teacher'
                ) AS teacher
            FROM `class`
            INNER JOIN `class_member` ON `class_member`.class_id = `class`.id
            WHERE `class`.id = :class_id
            GROUP BY `class`.id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':class_id' => $classId
        ]);

        $fetch = $sto->fetch(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function getClassMembers($classId) 
    {
        $classId = DataProcessor::sanitizeData($classId);

        // Prepare the SQL query
        $query = "
            SELECT `user`.*
            FROM `class_member`
            INNER JOIN `user` ON `user`.id = `class_member`.user_id
            WHERE `class_member`.class_id = :class_id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':class_id' => $classId
        ]);

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($fetch as $result) {
            $results[$result['id']] = $result;
        }

        return $results;
    }

    public static function isRegistered($className, $classId) 
    {
        $className = DataProcessor::sanitizeData($className);
        $classId = DataProcessor::sanitizeData($classId);

        $query = "
            SELECT * 
            FROM `class`
            WHERE name = :class_name AND NOT id = :class_id;
        ";

        // Fetch the result
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            'class_name' => $className,
            'class_id' => $classId
        ]);
        
        $results = $sto->rowCount();
        return ($results > 0);
    }

    public static function update($classId, $data) 
    {        
        $classId = DataProcessor::sanitizeData($classId);
        $data = DataProcessor::sanitizeData($data);

        foreach ($data as $key => $value) {
            // Prepare the SQL query
            $query = "
                UPDATE `class`
                SET $key = :value
                WHERE id = :class_id;
            ";

            try {
                // Execute statement
                $sto = self::$pdo->prepare($query);
                $sto->execute([
                    ':class_id' => $classId,
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
}

SchoolClass::initialize();
