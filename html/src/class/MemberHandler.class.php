<?php

class MemberHandler
{    
    public static function removeMember($postData, $session) 
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

        if (
            !DataProcessor::registeredValue('group_member', [
                'user_id' => $postData['group_id'], 
                'role' => "owner", 
                'group_id' => "group_id"
            ])
        ) {
            return self::handleError("You do not have the authorization to remove users from a group.");
        }

        if (
            DataProcessor::registeredValue('group_member', [
                'user_id' => $postData['user_id'], 
                'role' => "owner", 
                'group_id' => $postData['group_id']
            ])
        ) {
            self::updateRole(['role' => "owner", ]);
            self::handleError("Member removed!");
        }

        Member::delete($postData);

        self::handleError("Member removed!");
        return true;
    }

    public static function updateRole($data) 
    {
        $data = DataProcessor::sanitizeData($data);

        Member::updateRole([
            'role' => $data['role'], 
            'user_id' => $data['user_id'],
            'group_id' => $data['group_id']
        ]);
    }

    public static function isMember()
    {

    }
    
    private static function handleError($msg, $location = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["ERROR"]["MEMBER_ERROR"] = $msg;

        $location = ($location != null) ? $location : $_SERVER['HTTP_REFERER'];
        Redirect::to($location);
        return false;
    }
}