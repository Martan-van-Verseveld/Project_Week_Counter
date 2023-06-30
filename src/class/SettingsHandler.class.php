<?php

class SettingsHandler extends Handler
{
    public static function updateSettings($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['user_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("SETTINGS_ERROR", "An error occured, try again later.");
        }

        if (!parent::isAuthorized($session, $postData['user_id'])) {
            return parent::handleError("SETTINGS_ERROR", "You are not authorized to make changes to this user's settings.");
        }


        $userId = $postData['user_id'];
        unset($postData['action']);
        unset($postData['user_id']);

        $update = Settings::updateSettings($userId, $postData);
        if (!$update) {
            return parent::handleError("SETTINGS_ERROR", "Settings have not been updated. Try again later.");
        }
        
        return parent::handleError("SETTINGS_ERROR", "Settings updated.");
    }
}