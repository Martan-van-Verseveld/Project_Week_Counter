<?php

class Inbox
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function getInvites($userId)
    {
        // Prepare the SQL query
        $query = "
            SELECT *
            FROM `group_request`
            WHERE user_id = :user_id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $userId
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function getJoinRequests($userId)
    {
        // Prepare the SQL query
        $query = "
            SELECT `group_member`.*
            FROM `group_member`
            INNER JOIN `group_info` ON `group_info`.id = `group_member`.group_id
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute();
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $fetch = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }

    public static function getInbox($userId)
    {
        return [
            'invites' => self::getInvites($userId),
            // 'joins' => self::getJoinRequests($userId)
        ];
    }

    public static function delete($userId, $requestId)
    {

    }
}

Inbox::initialize();
