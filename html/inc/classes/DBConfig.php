<?php

class DBConfig
{
    protected $conn;

    public function __construct($_HOST, $_PORT, $_DBNAME, $_USER, $_PASSWD) 
    {
        try {
            $this->conn = new PDO(
                "mysql:host=".$_HOST.";
                port=".$_PORT.";
                dbname=".$_DBNAME, 
                $_USER, 
                $_PASSWD
            );
    
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection()
    {
        return $this->conn;
    }
}