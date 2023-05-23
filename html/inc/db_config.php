<?php

DEFINE("DB_Config", [
    "User" => "pw_counter",
    "Passwd" => 'YU@aHb01j6[UKXlu',
    "Dbname" => "pw_counter",
    "Host" => "localhost",
    "Port" => 3306
]);


try {
    $conn = new PDO(
        "mysql:host=".DB_Config["Host"].";
        port=".DB_Config["Port"].";
        dbname=".DB_Config["Dbname"], 
        DB_Config["User"], 
        DB_Config["Passwd"]
    );  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

return $conn;