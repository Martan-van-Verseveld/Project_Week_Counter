<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/class/GroupInfo.class.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/src/class/Dbh.class.php";

class GroupHandler extends FormHandler
{
    protected $pdo;
    private static $groupInfo;

    public function __construct()
    {
        $dbh = new Dbh;
        $dbh->startConnection();
        $this->pdo = $dbh->getConnection();

        self::$groupInfo = new GroupInfo;
    }

    public function handleCreateForm($postData) {
        $postData = FormHandler::sanitizeData($postData);
        
        if (!FormHandler::validateFields(['name', 'description'], $postData)) {
            FormHandler::triggerError("Required fields not present!", "GROUP_ERROR");
            return false;
        }

        if (self::$groupInfo->getGroupInfo(['name' => $postData['name']])) {
            FormHandler::triggerError("Group is already registered!", "GROUP_ERROR");
            return false;
        }

        if (!self::createGroup($postData)) {
            FormHandler::triggerError("Record was not inserted, try again later!", "GROUP_ERROR");
            return false;
        }


        FormHandler::triggerError("Group created!", "GROUP_ERROR", "../index.php?page=group_form&gid={$this->pdo->lastInsertId()}");
        return true;
    }

    public function handleUpdateForm($postData) {
        $postData = FormHandler::sanitizeData($postData);
        
        if (!FormHandler::validateFields(['id', 'name', 'description'], $postData)) {
            FormHandler::triggerError("Required fields not present!", "GROUP_ERROR");
            return false;
        }


        if (!self::updateGroup($postData)) {
            FormHandler::triggerError("Record was not updated, try again later!", "GROUP_ERROR");
            return false;
        }


        FormHandler::triggerError("Group updated!", "GROUP_ERROR");
        return true;
    }
    
    protected function createGroup($data)
    {
        // Prepare the SQL query
        $query = "
            INSERT INTO `group_info` (id, name, description)
            VALUES (NULL, :name, :description);
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $sto->execute([
            ':name' => $data['name'],
            ':description' => $data['description']
        ]);

        // Check insert success
        $insert = $this->pdo->lastInsertId();

        return ($insert) ? true : false;
    }
    
    protected function updateGroup($data)
    {
        // Prepare the SQL query
        $query = "
            UPDATE `group_info`
            SET `group_info`.name = :name, `group_info`.description = :desciption
            WHERE `group_info`.id = :id;
        ";

        // Execute statement
        $sto = $this->pdo->prepare($query);
        $insert = $sto->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':desciption' => $data['description']
        ]);

        return ($insert) ? true : false;
    }
}