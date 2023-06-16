<?php

class RequestHandler
{
    public static function processRequest($postData, $session): bool
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_REGEXP])
        ) {
            return self::handleError("An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
        ) {
            return self::handleError("Group does not exist...");
        }

        if (
            DataProcessor::registeredValue('group_request', ['group_id' => $postData['group_id'], 'user_id' => $session['user']['id']])
        ) {
            return self::handleError("Sorry you already have a request going out for this group!");
        }

        if (
            DataProcessor::registeredValue('group_member', ['group_id' => $postData['group_id'], 'user_id' => $session['user']['id']])
        ) {
            return self::handleError("Sorry you already a member of this group!");
        }

        self::sendRequest($postData['group_id'], $session['user']['id']);

        self::handleError("Request sent");
        return true;
    }
    
    public static function declineRequest($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['user_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
        ) {
            return self::handleError("An error occured, try again later.");
        }

        // if (
        //     !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
        // ) {
        //     return self::handleError("Group does not exist...");
        // }

        // if (
        //     DataProcessor::registeredValue('group_request', ['group_id' => $postData['group_id'], 'user_id' => $session['user']['id']])
        // ) {
        //     return self::handleError("Sorry you already have a request going out for this group!");
        // }

        Request::remove($postData);

        self::handleError("Request declined!");
        return true;
    }
    
    public static function acceptRequest($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['user_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
        ) {
            return self::handleError("An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
            || !DataProcessor::registeredValue('user', ['id' => $postData['user_id']])
        ) {
            return self::handleError("Group or user does not exist...");
        }

        Request::accept($postData);

        self::handleError("Request accepted!");
        return true;
    }

    private static function sendRequest($groupId, $userId) 
    {
        Request::create([
            'user_id' => $userId, 
            'group_id' => $groupId
        ]);
    }
    
    private static function handleError($msg, $location = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["ERROR"]["REQUEST_ERROR"] = $msg;

        $location = ($location != null) ? $location : $_SERVER['HTTP_REFERER'];
        Redirect::to($location);
        return false;
    }
}