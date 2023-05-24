<?php
# Написано Мартан ван Версевелд #

class SessionManager {
    public function __construct() {
        session_start();
    }
    
    public function setSessionData($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public function getSessionData($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        
        return null;
    }
    
    public function removeSessionData($key) {
        unset($_SESSION[$key]);
    }
}