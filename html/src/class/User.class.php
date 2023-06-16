<?php

class User 
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
            INSERT INTO user (email, firstname, lastname, role, password)
            VALUES (:email, :firstname, :lastname, :role, :password);
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':email' => $data['email'],
            ':firstname' => $data['firstname'],
            ':lastname' => $data['lastname'],
            ':role' => $data['role'],
            ':password' => $hashed_passwd
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function getUser($userId) 
    {
        $userId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $query = "
            SELECT id, firstname, lastname, email
            FROM `user`
            WHERE id = :userId;
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

User::initialize();
