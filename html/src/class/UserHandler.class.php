<?php

class UserHandler extends Handler
{
    public static function updateProfile($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        $userProfile = "/index.php?page=profile&id={$postData['user_id']}";

        if (
            !DataProcessor::validateFields($postData, ['user_id', 'firstname', 'lastname', 'description', 'email'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['firstname'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['lastname'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['description'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['email'] => FILTER_VALIDATE_EMAIL])
        ) {
            return parent::handleError("PROFILE_ERROR", "An error occured, try again later.");
        }

        if ($_SESSION['user']['id'] != $postData['user_id'] && !DataProcessor::registeredValue('user', [
            'user_id' => $_SESSION['user']['id'],
            'role' => 'teacher'
        ])) {
            return parent::handleError("PROFILE_ERROR", "You do not have permission to change this user's data.", $userProfile);
        }

        if (!User::update($postData['user_id'], [
            'firstname' => $postData['firstname'],
            'lastname' => $postData['lastname'],
            'email' => $postData['email'],
            'description' => $postData['description']
        ])) {
            return parent::handleError("PROFILE_ERROR", "User data couldn't be updated, try again later.", $userProfile);
        }

        return parent::handleError("PROFILE_ERROR", "User data has been updated.", $userProfile);
    }

    public static function updatePassword($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        $userProfile = "/index.php?page=profile&id={$postData['user_id']}";

        if (
            !DataProcessor::validateFields($postData, ['user_id', 'password', 'password_conf'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['password'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['password_conf'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("PASSWORD_ERROR", "An error occured, try again later.");
        }

        if ($_SESSION['user']['id'] != $postData['user_id']) {
            return parent::handleError("PASSWORD_ERROR", "You do not have permission to change this user's password.", $userProfile);
        }

        if ($postData['password'] != $postData['password_conf']) {
            return parent::handleError("PASSWORD_ERROR", "Passwords don't match.");
        }

        if (!User::update($postData['user_id'], [
            'password' => DataProcessor::hashPassword($postData['password'])
        ])) {
            return parent::handleError("PASSWORD_ERROR", "User data couldn't be updated, try again later.", $userProfile);
        }

        return parent::handleError("PASSWORD_ERROR", "Password was update.", $userProfile);
    }
}