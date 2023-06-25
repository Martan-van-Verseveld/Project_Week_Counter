<?php

class Session
{
    public static function put($key, $value) 
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION[$key] = $value;
    }

    public static function get($key) 
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        return $_SESSION[$key];
    }

    public static function pdoDebug($error) 
    {
        if (!PDO_DEBUG) return;
        
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["PDO_ERROR"] = $error;
    }
}