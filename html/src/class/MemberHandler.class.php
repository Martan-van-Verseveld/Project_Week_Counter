<?php

class MemberHandler extends Handler
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
            return parent::handleError("MEMBER_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('group_info', ['id' => $postData['group_id']])
            || !DataProcessor::registeredValue('user', ['id' => $postData['user_id']])
        ) {
            return parent::handleError("MEMBER_ERROR", "Group or user does not exist...");
        }

        if (Member::isOwner($postData['user_id'], $postData['group_id'])) {
            parent::handleError("MEMBER_ERROR", "This user is the owner of this group. You cannot remove this user.");
        }

        if (
            !DataProcessor::registeredValue('group_member', [
                'user_id' => $session['user']['id'], 
                'role' => "owner", 
                'group_id' => $postData['group_id']
            ])
        ) {
            return parent::handleError("MEMBER_ERROR", "You do not have the authorization to remove users from a group.");
        }

        Member::delete($postData);

        parent::handleError("MEMBER_ERROR", "Member removed!");
        return true;
    }

    public static function updateOwnership($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);

        if (
            !DataProcessor::validateFields($postData, ['user_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("MEMBER_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('group_member', [
                'user_id' => $session['user']['id'], 
                'role' => "owner", 
                'group_id' => $postData['group_id']
            ])
        ) {
            return parent::handleError("MEMBER_ERROR", "You do not have the authorization to transfer ownership to other users in this group.");
        }

        if (Member::isOwner($postData['user_id'], $postData['group_id'])) {
            parent::handleError("MEMBER_ERROR", "This user is already the owner");
        }

        if (!Member::updateOwnership([
            'user_id' => $postData['user_id'],
            'group_id' => $postData['group_id']
        ])) {
            parent::handleError("MEMBER_ERROR", "An error occured, try again later.");
        }

        parent::handleError("MEMBER_ERROR", "Member has became the new owner!");
        return true;
    }

    public static function leaveMember($postData, $session)
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['user_id', 'group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("MEMBER_ERROR", "An error occured, try again later.");
        }

        if (Member::isOwner($postData['user_id'], $postData['group_id'])) {
            parent::handleError("MEMBER_ERROR", "This user is the owner of the group.");
        }

        Member::delete($postData);
        

        parent::handleError("MEMBER_ERROR", "Member left");
        return true;
    }
}