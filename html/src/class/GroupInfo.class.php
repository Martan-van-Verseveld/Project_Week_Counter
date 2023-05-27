<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/class/Dbh.class.php";

class GroupInfo
{
    protected $pdo;

    public function __construct()
    {
        $dbh = new Dbh;
        $dbh->startConnection();
        $this->pdo = $dbh->getConnection();
    }

    public function getGroupInfo($conditions = [])
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
            FROM `group_info`
            $where;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute();

        // Fetch the result
        $result = $sto->fetch(PDO::FETCH_ASSOC);

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
        $sto = $this->pdo->prepare($query);
        $sto->execute();

        // Fetch the result
        $results = $sto->fetchAll(PDO::FETCH_ASSOC);

        $groups = [];
        foreach ($results as $group) $groups[$group['id']] = $group;

        return ($groups) ? $groups : null;
    }

    public function getGroupMembers()
    {
        // Prepare the SQL query
        $query = "
            SELECT * 
            FROM `group_info`;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute();

        // Fetch the result
        $results = $sto->fetchAll(PDO::FETCH_ASSOC);

        $groups = [];
        foreach ($results as $group) $groups[$group['id']] = $group;

        return ($groups) ? $groups : null;
    }

    public function getGroupIncommingRequests($groupId) 
    {
        // Prepare the SQL query
        $query = "
            SELECT `group_request`.status, `group_request`.id, 
                    `user`.id as user_id, `user`.email as email, `user`.firstname as firstname, `user`.lastname as lastname
            FROM `group_request`
            INNER JOIN `user` ON `user`.id = `group_request`.user_id
            WHERE `group_request`.group_id = :groupId;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute([
            ':groupId' => $groupId
        ]);

        // Fetch the result
        $results = $sto->fetchAll(PDO::FETCH_ASSOC);

        $requests = [];
        foreach ($results as $request) $requests[$request['id']] = $request;

        return ($requests) ? $requests : null;
    }
    public function getGroupOutgoingRequests($userId) 
    {
        // Prepare the SQL query
        $query = "
            SELECT `group_request`.status, `group_request`.id, 
                    `group_info`.id as group_id, `group_info`.name, `group_info`.description
            FROM `group_request`
            INNER JOIN `group_info` ON `group_info`.id = `group_request`.group_id
            WHERE `group_request`.user_id = :userId;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute([
            ':userId' => $userId
        ]);

        // Fetch the result
        $results = $sto->fetchAll(PDO::FETCH_ASSOC);

        $requests = [];
        foreach ($results as $request) $requests[$request['id']] = $request;

        return ($requests) ? $requests : null;
    }
}
