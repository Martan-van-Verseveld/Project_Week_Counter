<?php

class RequestHandler extends Handler
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
            return parent::handleError("REQUEST_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
        ) {
            return parent::handleError("REQUEST_ERROR", "Group does not exist...");
        }

        if (
            DataProcessor::registeredValue('group_request', ['group_id' => $postData['group_id'], 'user_id' => $session['user']['id']])
        ) {
            return parent::handleError("REQUEST_ERROR", "Sorry you already have a request going out for this group!");
        }

        if (
            DataProcessor::registeredValue('group_member', ['group_id' => $postData['group_id'], 'user_id' => $session['user']['id']])
        ) {
            return parent::handleError("REQUEST_ERROR", "Sorry you already a member of this group!");
        }

        self::sendRequest($postData['group_id'], $session['user']['id'], 'request');

        parent::handleError("REQUEST_ERROR", "Request sent");
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
            return parent::handleError("REQUEST_ERROR", "An error occured, try again later.");
        }

        // if (
        //     !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
        // ) {
        //     return parent::handleError("REQUEST_ERROR", "Group does not exist...");
        // }

        // if (
        //     DataProcessor::registeredValue('group_request', ['group_id' => $postData['group_id'], 'user_id' => $session['user']['id']])
        // ) {
        //     return parent::handleError("REQUEST_ERROR", "Sorry you already have a request going out for this group!");
        // }

        Request::remove($postData);

        parent::handleError("REQUEST_ERROR", "Request declined!");
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
            return parent::handleError("REQUEST_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
            || !DataProcessor::registeredValue('user', ['id' => $postData['user_id']])
        ) {
            return parent::handleError("REQUEST_ERROR", "Group or user does not exist...");
        }

        Request::accept($postData);

        parent::handleError("REQUEST_ERROR", "Request accepted!");
        return true;
    }

    public static function inviteRequest($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("REQUEST_ERROR", "An error occured, try again later.");
        }

        $group = Group::getGroup($postData['group_id']);
        $inboxId = Inbox::create($postData['user_id'], "Group ivitation.", "");
        Inbox::setBody($inboxId, "
            <div class='invite-container'>
                <p id='invite-group'>Group invite from: \"{$group['name']}\"</p>
                <form method='POST' action='/src/inc/formHandler.inc.php'>
                    <input type='hidden' name='action' value='invite-accept'>
                    <input type='hidden' name='inbox_id' value='$inboxId'>
                    <input type='hidden' value='{$postData['user_id']}' name='user_id'>
                    <input type='hidden' value='{$postData['group_id']}' name='group_id'>
                    <input type='submit' value='Accept'>
                </form>
                <form method='POST' action='/src/inc/formHandler.inc.php'>
                    <input type='hidden' name='action' value='invite-decline'>
                    <input type='hidden' name='inbox_id' value='$inboxId'>
                    <input type='hidden' value='{$postData['user_id']}' name='user_id'>
                    <input type='hidden' value='{$postData['group_id']}' name='group_id'>
                    <input type='submit' value='Decline'>
                </form>
            </div>
        ");
        
        self::sendRequest($postData['group_id'], $postData['user_id'], 'invite');

        parent::handleError("REQUEST_ERROR", "Request sent");
        return true;
    }

    public static function inviteAccept($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("REQUEST_ERROR", "An error occured, try again later.");
        }

        Request::acceptInvite($postData['user_id'], $postData['group_id']);
        Inbox::delete($postData['inbox_id']);

        parent::handleError("REQUEST_ERROR", "Invite accepted");
        return true;
    }

    public static function inviteDecline($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("REQUEST_ERROR", "An error occured, try again later.");
        }

        Request::declineInvite($postData['user_id'], $postData['group_id']);
        Inbox::delete($postData['inbox_id']);

        parent::handleError("REQUEST_ERROR", "Invite declined");
        return true;
    }

    private static function sendRequest($groupId, $userId, $type) 
    {
        if (
            DataProcessor::registeredValue('group_request', ['user_id' => $userId, 'group_id' => $groupId])
            || DataProcessor::registeredValue('group_member', ['user_id' => $userId])
        ) {
            parent::handleError('REQUEST_ERROR', 'This user is already in a group.');
        }
        
        Request::create([
            'user_id' => $userId, 
            'group_id' => $groupId, 
            'type' => $type
        ]);

        parent::handleError('REQUEST_ERROR', 'User was invited.');
    }
}