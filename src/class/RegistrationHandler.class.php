<?php

class RegistrationHandler extends Handler
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
            return parent::handleError("REGISTER_ERROR", "An error occured, try again later.");
        }

        if ($postData['password'] !== $postData['password_conf']) {
            return parent::handleError("REGISTER_ERROR", "Passwords do not match.");
        }

        if (DataProcessor::registeredValue('user', ['email' => $postData['email']])) {
            return parent::handleError("REGISTER_ERROR", "EMail already registered.");
        }

        if (!User::create($postData)) {
            return parent::handleError("REGISTER_ERROR", "Failed to insert record. Try again later!");
        }

        parent::handleError("REGISTER_ERROR", "Account registered.", "/index.php?page=login");
        return true;
    }
}