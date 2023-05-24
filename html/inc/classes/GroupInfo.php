<?php

class GroupInfo
{
    protected $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getGroupInfo($groupId)
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `group_info` 
            WHERE id = :groupId;
        ";

        // Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result : null;
    }

    public function getGroupMembers($groupId)
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `group_member` 
            WHERE group_id = :groupId;
        ";

        // Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $members = [];
        foreach ($results as $member) $members[$member['id']] = $member;

        return ($members) ? $members : null;
    }

    public function getUserGroup($userId)
    {
        // Prepare the SQL query
        $query = "
            SELECT `group_info`.*
            FROM `group_info`
            INNER JOIN `group_member` ON `group_info`.id = `group_member`.group_id
            WHERE `group_member`.user_id = :userId;
        ";

        // Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result : null;
    }

    public function getGroups()
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `group_info`;
        ";

        // Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch the result
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groups = [];
        foreach ($results as $group) $groups[$group['id']] = $group;

        return ($groups) ? $groups : null;
    }
}
