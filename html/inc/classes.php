<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");

class page
{
    public $name;
    public $path;

    public function __construct($requested_page) {
        // Set variables
        $this->name = $requested_page;
        $this->path = $_SERVER['DOCUMENT_ROOT'] . "/pages/$requested_page.php";
    }

    public function load($debug = false) {
        // Enable errors
        ini_set('display_errors', $debug);

        // Include page
        include($this->path);

        // Disable errors
        ini_set('display_errors', 0);
    }

    public function load_full($debug = false) {
        // Enable errors
        ini_set('display_errors', $debug);

        // Include and Require header + page + footer
        require($_SERVER['DOCUMENT_ROOT'] . "/inc/header.php");

        $include = include($this->path);
        (!$include) ? $this->errorHandler() : '';

        require($_SERVER['DOCUMENT_ROOT'] . "/inc/footer.php");

        // Disable errors
        ini_set('display_errors', 0);
    }

    private function errorHandler() {
        echo "404 not found";
    }

    public function index($beautify = true) {
        // Put in temporary array
        $array = [
            'name' => $this->name,
            'path' => $this->path
        ];

        // Display Array
        ($beautify) ? print_p($array) : print_r($array);
    }
}

class pdo_crud {
    public $conn = null;

    public function __construct($_HOST, $_PORT, $_DBNAME, $_USER, $_PASSWD) {
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
            echo "Connection failed: " . $e->getMessage();
        }
    }
}