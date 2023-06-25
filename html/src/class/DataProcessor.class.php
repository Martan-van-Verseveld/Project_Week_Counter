<?php

class DataProcessor
{
    private static $pdo;

    public static function initialize() {
        Dbh::startConnection();
        self::$pdo = Dbh::getConnection();
    }

    public static function sanitizeData($data)
    {
        $returnData = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $returnData[$key] = self::sanitizeData($value);
                } else {
                    $returnData[$key] = self::sanitizeInput($value);
                }
            }
        } else {
            $returnData = self::sanitizeInput($data);
        }

        return $returnData;
    }

    private static function sanitizeInput($input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_COMPAT | ENT_HTML5, 'UTF-8');

        return $input;
    }

    public static function validateFields($data, $fields): bool
    {
        // Check if $data has all required $fields
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }
        }
    
        return true;
    }

    public static function validateType($data, $options = ["regexp" => "/^(?![\x80-\xFF]).*$/"]): bool
    {
        foreach ($data as $key => $value) {
            if (!filter_var($key, $value, ["options" => $options])) {
                return false;
            }
        }

        return true;
    }

    public static function registeredValue($table, $conditions): bool
    {
        // conditions
        $where = Dbh::buildWhereClause($conditions);

        $query = "
            SELECT * 
            FROM $table
            $where;
        ";

        // Fetch the result
        $sto = self::$pdo->prepare($query);
        $sto->execute();
        
        $results = $sto->rowCount();
        return ($results > 0);
    }

    public static function hashPassword($password) 
    {
        $hash = password_hash(PASS_PEPPER . $password . PASS_SALT, PASS_ENC);
        return $hash;
    }
}

DataProcessor::initialize();
