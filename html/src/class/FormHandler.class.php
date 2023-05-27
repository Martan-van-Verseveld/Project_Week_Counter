<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/class/UserInfo.class.php";

class FormHandler
{
    protected $passwd_config = [
        'salt' => "4b58e936d051dd2ad039ef12d5c0174f",
        'pepper' => "1166148776b0476d7a3a60be63d31ae4",
        'encryption' => CRYPT_SHA256
    ];

    protected function sanitizeData($inputData) 
    {
        $returnData = [];

        if (is_array($inputData)) {
            foreach ($inputData as $key => $data) {
                if (is_array($data)) {
                    $returnData[$key] = self::sanitizeData($data);
                } else {
                    $returnData[$key] = self::sanitizeInput($data);
                }
            }
        } else {
            $returnData = self::sanitizeInput($inputData);
        }

        return $returnData;
    }

    private function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $input;
    }

    protected function validateFields($fields, $data) {
        // Check if post has all required fields
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        return true;
    }

    protected function validateEmail($input) {
        // Validate format
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) return false;

        return true;
    }

    protected function registeredEmail($input) {
        // Validate registered email
        $userInfo = new UserInfo;
        $user = $userInfo->getUserInfo(['email' => $input]);
        if (!empty($user)) return true;

        return false;
    }

    protected function comparePasswords($pass, $pass_conf){
        if ($pass != $pass_conf) return false;

        return true;
    }

    protected function validatePassword($data) {
        // Get password from database
        $userInfo = new UserInfo;
        $user = $userInfo->getUserInfo(['email' => $data['email']]);

        if (!password_verify($this->passwd_config['pepper'] . $data['password'] . $this->passwd_config['salt'], $user['password'])) {
            return false;
        }

        return true;
    }

    protected function triggerError($msg, $error) {
        session_start();

        $_SESSION[$error] = $msg;
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
}