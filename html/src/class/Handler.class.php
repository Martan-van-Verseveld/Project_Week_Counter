<?php

class Handler {
    protected static function handleError($type, $msg, $location = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["ERROR"][$type] = $msg;

        $location = ($location != null) ? $location : $_SERVER['HTTP_REFERER'];
        Redirect::to($location);
        return false;
    }
}