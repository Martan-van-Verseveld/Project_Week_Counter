<?php

class Dbh
{
    protected static $pdo;

    public static function startConnection()
    {
        $dsn = "mysql:host=". DB_HOST .";port=". DB_PORT .";dbname=". DB_NAME;
        $user = DB_USER;
        $password = DB_PASS;
    
        try {
            self::$pdo = new PDO($dsn, $user, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    
        return true;
    }
    
    public static function getConnection()
    {
        return self::$pdo;
    }

    public static function stopConnection() {
        self::$pdo = null;
        return self::$pdo === null;
    }

    
    public static function buildWhereClause($conditions): string
    {
        $conditions = DataProcessor::sanitizeData($conditions);
        $where = '';
    
        if (!empty($conditions)) {
            $where = 'WHERE ';
            $placeholders = [];
    
            foreach ($conditions as $key => $value) {
                $placeholders[] = "$key = '$value'";
            }
    
            $where .= implode(' AND ', $placeholders);
        }
    
        return $where;
    }
}