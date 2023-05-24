<?php

class UserInfo
{
    protected $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getUserInfo($userId)
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `user` 
            WHERE id = :userId;
        ";

        // Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result : null;
    }

    public function getUsers()
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `user`;
        ";

        // Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch the result
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $user) $users[$user['id']] = $user;

        return ($users) ? $users : null;
    }
}
