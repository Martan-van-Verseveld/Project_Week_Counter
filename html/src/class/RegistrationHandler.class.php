<?php

class RegistrationHandler
{
    public static function processForm($postData): bool
    {
        $postData = DataProcessor::sanitizeData($postData);

        if (
            !DataProcessor::validateFields($postData, ['email', 'firstname', 'lastname', 'password', 'password_conf', 'role'])
            || !DataProcessor::validateType([$postData['firstname'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['lastname'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['email'] => FILTER_VALIDATE_EMAIL])
            || !DataProcessor::validateType([$postData['password'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['password_conf'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['role'] => FILTER_VALIDATE_REGEXP])
        ) {
            return self::handleError("An error occured, try again later.");
        }

        if ($postData['password'] !== $postData['password_conf']) {
            return self::handleError("Passwords do not match.");
        }

        if (DataProcessor::registeredValue('user', ['email' => $postData['email']])) {
            return self::handleError("EMail already registered.");
        }

        if (!User::create($postData)) {
            return self::handleError("Failed to insert record. Try again later!");
        }

        self::handleError("Account registered.", "/index.php?page=login");
        return true;
    }

    private static function handleError($msg, $location = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["ERROR"]["REGISTER_ERROR"] = $msg;

        $location = ($location != null) ? $location : $_SERVER['HTTP_REFERER'];
        Redirect::to($location);
        return false;
    }
}