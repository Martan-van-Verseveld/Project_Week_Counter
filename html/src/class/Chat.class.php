<?php

class Chat
{
    private static $pdo;

    public static function initialize(): void
    {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function getChat($sender, $recipient) 
    {
        $sender = DataProcessor::sanitizeData($sender);
        $recipient = DataProcessor::sanitizeData($recipient);

        // Prepare the SQL query
        $query = "
            SELECT `chat`.*, CONCAT(`user`.firstname, ' ', `user`.lastname) as 'name'
            FROM `chat`
            INNER JOIN `user` ON `chat`.sender_id = `user`.id
            WHERE sender_id = :sender_id AND recipient_id = :recipient_id
                OR sender_id = :recipient_id AND recipient_id = :sender_id;
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':sender_id' => $sender,
                ':recipient_id' => $recipient
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $results = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
    
    public static function sendChat($sender, $recipient, $body) 
    {
        $sender = DataProcessor::sanitizeData($sender);
        $recipient = DataProcessor::sanitizeData($recipient);
        $body = DataProcessor::sanitizeData($body);

        // Prepare the SQL query
        $query = "
            INSERT INTO `chat` (sender_id, recipient_id, body, viewed)
            VALUES (:sender_id, :recipient_id, :body, NULL);
        ";

        try {
            // Execute statement
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':sender_id' => $sender,
                ':recipient_id' => $recipient,
                ':body' => $body
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $insert = $sto->rowCount();
        return ($insert > 0);
    }

    public static function getChats($userId) 
    {
        $userId = DataProcessor::sanitizeData($userId);

        // Prepare the SQL query
        $query = "
            SELECT `chat`.sender_id, `chat`.recipient_id, `chat`.body AS 'body', `chat`.sent,
                CONCAT(sender.firstname, ' ', sender.lastname) as 'sender_name',
                CONCAT(recipient.firstname, ' ', recipient.lastname) as 'recipient_name'
            FROM `chat`
            INNER JOIN `user` sender ON sender.id = `chat`.sender_id
            INNER JOIN `user` recipient ON recipient.id = `chat`.recipient_id
            INNER JOIN (
                SELECT 
                    CASE
                        WHEN sender_id = :user_id THEN recipient_id
                        WHEN recipient_id = :user_id THEN sender_id
                    END AS user_id,
                    MAX(sent) AS max_sent
                FROM `chat`
                WHERE sender_id = :user_id OR recipient_id = :user_id
                GROUP BY user_id
            ) AS body ON 
                (`chat`.sender_id = body.user_id OR `chat`.recipient_id = body.user_id) AND
                `chat`.sent = body.max_sent
            WHERE `chat`.sender_id = :user_id OR `chat`.recipient_id = :user_id
            ORDER BY `chat`.sent DESC;
        ";
    
        try {
            // Execute statement+
            $sto = self::$pdo->prepare($query);
            $sto->execute([
                ':user_id' => $userId
            ]);
            Session::pdoDebug("No errors.");
        } catch (PDOException $e) {
            Session::pdoDebug($e);
        }

        $results = $sto->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public static function hasChatted($sender_id, $recipient_id): bool
    {
        $chatted = !DataProcessor::registeredValue('chat', [
            'sender_id' => $sender_id,
            'recipient_id' => $recipient_id
        ]) && !DataProcessor::registeredValue('chat', [
            'sender_id' => $recipient_id,
            'recipient_id' => $sender_id
        ]);

        return $chatted;
    }
}

Chat::initialize();
