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

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':email' => $data['email'],
                ':firstname' => $data['firstname'],
                ':lastname' => $data['lastname'],
                ':role' => $data['role'],
                ':password' => $hashed_passwd
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        Settings::create(self::$pdo->lastInsertId());

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function getUser($userId) 
    {
        $userId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $userQuery = "
            SELECT `user`.id, `user`.firstname, `user`.lastname, `user`.email, `user`.description, 
                CONCAT(`user`.firstname, ' ', `user`.lastname) as 'name'
            FROM `user`
            WHERE `user`.id = :userId;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($userQuery);
            $sto->execute([
                ':userId' => $userId
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }
        $results = $sto->fetch(PDO::FETCH_ASSOC);


        $results['settings'] = Settings::getSettings($userId);
        $results['group'] = Group::getUserGroup($userId);
        return $results;
    }

    public static function getUsers() 
    {
        // Prepare the SQL query
        $query = "
            SELECT `user`.id, `user`.firstname, `user`.lastname, `user`.role, `user`.email, `user`.description, 
                CONCAT(`user`.firstname, ' ', `user`.lastname) as 'name'
            FROM `user`;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute();
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $results = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public static function getInvUsers()
    {
        // Prepare the SQL query
        $query = "
            SELECT `user`.id, `user`.firstname, `user`.lastname, `user`.email, `user`.description
            FROM `user`
            INNER JOIN `settings` ON `settings`.user_id = `user`.id
            WHERE `settings`.invites = 1
            AND NOT EXISTS (
                SELECT 1
                FROM `group_member`
                WHERE `group_member`.user_id = `user`.id
            );
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute();
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $results = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public static function update($userId, $data): bool
    {
        $userId = DataProcessor::sanitizeData($userId);
        $data = DataProcessor::sanitizeData($data);
    
        foreach ($data as $key => $value) {    
            // Prepare the SQL query
            $query = "
                UPDATE `user`
                SET $key = :value
                WHERE id = :user_id;
            ";
    
            try {
                // Execute statement
                $sto = self::$pdo->prepare($query);
                $sto->execute([
                    ':user_id' => $userId,
                    ':value' => $value
                ]);
                Session::pdoDebug("No errors.");
            } catch (PDOException $e) {
                Session::pdoDebug($e);
            }
        }
    
        return true;
    }

    public static function updatePassword($userId, $password)
    {
        $userId = DataProcessor::sanitizeData($password);

        // Hash password
        $hashed_passwd = DataProcessor::hashPassword($password);
   
        // Prepare the SQL query
        $query = "
            UPDATE `user`
            SET password = :value
            WHERE id = :user_id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $userId,
                ':value' => $hashed_passwd
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }
    }

    public static function isInviteable($userId) 
    {
        return DataProcessor::registeredValue('settings', [
            'user_id' => $userId,
            'invites' => 1
        ]);
    }

    public static function isPrivate($userId) 
    {
        return DataProcessor::registeredValue('settings', [
            'user_id' => $userId,
            'private' => 1
        ]);
    }
}

User::initialize();
