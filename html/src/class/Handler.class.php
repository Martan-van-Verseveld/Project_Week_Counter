<?php

class Handler {
    public static function isAuthorized($session, $userId): bool
    {
        if ($session['user']['role'] == 'teacher') {
            return true;
        }
        
        if ($session['user']['id'] === $userId) {
            return true;
        }

        return false;
    }

    protected static function handleError($type, $msg, $location = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["ERROR"][$type] = $msg;

        $location = ($location != null) ? $location : $_SESSION['REFERER'];
        Redirect::to($location);
        return false;
    }
}