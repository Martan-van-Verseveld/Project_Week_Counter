<?php

DEFINE("DB_Config", [
    "User" => "pw_counter",
    "Passwd" => "yTX4ltg8.-vFHD92",
    "Dbname" => "pw_counter",
    "Host" => "localhost",
    "Port" => 3306
]);

$conn = new PDO(
    "mysql:host=".DB_Config["Host"].";
    port=".DB_Config["Port"].";
    dbname=".DB_Config["Dbname"], 
    DB_Config["User"], 
    DB_Config["Passwd"]
);  

return $conn;