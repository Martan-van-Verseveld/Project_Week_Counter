<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/class/Dbh.class.php";

class UserInfo
{
    protected $passwd_config = [
        'salt' => "4b58e936d051dd2ad039ef12d5c0174f",
        'pepper' => "1166148776b0476d7a3a60be63d31ae4",
        'encryption' => CRYPT_SHA256
    ];

    protected $pdo;

    public function __construct()
    {
        $dbh = new Dbh;
        $dbh->startConnection();
        $this->pdo = $dbh->getConnection();
    }

    public function getUserInfo($conditions = [])
    {
        // conditions
        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ';
            foreach ($conditions as $key => $value) {
                $where .= "$key = '$value' AND ";
            }
            $where = rtrim($where, 'AND ');
        }

        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `user`
            $where;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute();

        // Fetch the result
        $result = $sto->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result : null;
    }
    
    public function createUser($data)
    {
        // Hash password
        $hashed_passwd = password_hash($this->passwd_config['pepper'] . $data['password'] . $this->passwd_config['salt'], $this->passwd_config['encryption']);

        // Prepare the SQL query
        $query = "
            INSERT INTO user (email, firstname, lastname, role, password)
            VALUES (:email, :firstname, :lastname, :role, :password);
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute([
            ':email' => $data['email'],
            ':firstname' => $data['firstname'],
            ':lastname' => $data['lastname'],
            ':role' => $data['role'],
            ':password' => $hashed_passwd
        ]);

        // Check insert success
        $insert = $this->pdo->lastInsertId();

        return ($insert) ? true : false;
    }

    public function getUsers()
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `user`;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute();

        // Fetch the result
        $results = $sto->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $user) $users[$user['id']] = $user;

        return ($users) ? $users : null;
    }
}
