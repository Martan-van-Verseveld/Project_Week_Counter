<?php
# Написано Мартан ван Версевелд #


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

class CRUD {
    private $conn;
    private $query;

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

    // Create records
    public function create($table, $data) {
        try {
            $fields = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));

            $this->query = "
            INSERT INTO $table ($fields) 
            VALUES ($placeholders);
            ";
            
            $stmt = $this->conn->prepare($this->query);
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Read records
    public function read($table, $columns, $conditions = []) {
        try {
            $fields = is_array($columns) ? implode(", ", $columns) : $columns;

            $where = '';
            if (!empty($conditions)) {
                $where = 'WHERE ';
                foreach ($conditions as $key => $value) {
                    $where .= "$key = '$value' AND ";
                }
                $where = rtrim($where, 'AND ');
            }
    
            $this->query = "
            SELECT $fields
            FROM `$table`
            $where
            ";

            $stmt = $this->conn->prepare($this->query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Update records
    public function update($table, $data, $conditions = []) {
        try {
            $fields = '';
            foreach ($data as $key => $value) {
                $fields .= "$key = :$key, ";
            }
            $fields = rtrim($fields, ', ');

            $where = 'WHERE ';
            foreach ($conditions as $key => $value) {
                $where .= "$key = '$value' AND ";
            }
            $where = rtrim($where, 'AND ');

            $this->query = "
            UPDATE `$table` 
            SET $fields
            ";
            $this->query .= (empty($conditions)) ? '' : $where;

            $stmt = $this->conn->prepare($this->query);
            $stmt->execute($data);

            return $stmt->rowCount();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Delete records
    public function delete($table, $conditions = []) {
        try {
            $this->conn->beginTransaction();
    
            // Fetch the data before deleting for potential rollback
            $data = $this->read($table, '*', $conditions);

            $where = 'WHERE ';
            foreach ($conditions as $key => $value) {
                $where .= "$key = '$value' AND ";
            }
            $where = rtrim($where, 'AND ');
    
            $this->query = "
            DELETE FROM `$table`
            ";
            $this->query .= (empty($conditions)) ? '' : $where;
            
            $stmt = $this->conn->prepare($this->query);
            $stmt->execute();
    
            $rowCount = $stmt->rowCount();
    
            if ($rowCount > 0) {
                $this->conn->commit();
                return $rowCount;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function showQuery() {
        print_p(rtrim($this->query), '<hr/>');
    }

    public function customQuery($query, $data) {
        try {
            $this->query = $query;
            $stmt = $this->conn->prepare($this->query);
            $stmt->execute($data);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}