<?php

class Dbh
{
    protected static $pdo;

    private $HOST, $PORT, $DBNAME, $USER, $PASSWD;

    public function __construct() 
    {
        $this->HOST =   '127.0.0.1';
        $this->PORT =   3306; 
        $this->DBNAME = 'pw_counter'; 
        $this->USER =   'pw_counter'; 
        $this->PASSWD = 'YU@aHb01j6[UKXlu';
    }

    public function startConnection() 
    {
        try {
            self::$pdo = new PDO("mysql:host={$this->HOST};port={$this->PORT};dbname={$this->DBNAME}", $this->USER, $this->PASSWD);
    
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "pdoection failed: " . $e->getMessage();
            return false;
        }

        return true;
    }
    
    public function getConnection()
    {
        return self::$pdo;
    }

    public function stopConnection() {
        self::$pdo = null;
        
        return (self::$pdo == null) ? true : false;
    }
}