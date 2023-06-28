<?php

class Inbox
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function getInbox($userId) 
    {
        // Prepare the SQL query
        $query = "
            SELECT `inbox`.*
            FROM `inbox`
            WHERE `inbox`.user_id = :user_id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function create($userId, $title, $body)
    {
        // Prepare the SQL query
        $query = "
            INSERT INTO `inbox` (user_id, title, body)
            VALUES (:user_id, :title, :body);
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':user_id' => $userId,
            ':title' => $title,
            ':body' => $body
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        if ($insert <= 0) return false;

        return self::$pdo->lastInsertId();
    }

    public static function setBody($inboxId, $body)
    {
        // Prepare the SQL query
        $query = "
            UPDATE `inbox`
            SET `inbox`.body = :body
            WHERE `inbox`.id = :id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':id' => $inboxId,
            ':body' => $body
        ]);

        // Check insert success
        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function delete($inboxId)
    {
        // Prepare the SQL query
        $query = "
            DELETE FROM `inbox`
            WHERE `inbox`.id = :id;
        ";

        // Execute statement
        $sto = self::$pdo->prepare($query);
        $sto->execute([
            ':id' => $inboxId
        ]);

        // Check delete success
        $delete = $sto->rowCount();
        return ($delete > 0);
    }
}

Inbox::initialize();
