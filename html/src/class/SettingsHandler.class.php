<?php

class SettingsHandler extends Handler
{
    public static function updateSettings($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        
    }
}