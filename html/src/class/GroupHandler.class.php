<?php

class GroupHandler extends Handler
{
    public static function registerGroup($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['name', 'description'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['name'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['description'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("GROUP_ERROR", "An error occured, try again later.");
        }

        if (
            DataProcessor::registeredValue('group_info', ['name' => $postData['name']])
        ) {
            return parent::handleError("GROUP_ERROR", "Sorry there is already a group registered with this name.");
        }

        if (
            DataProcessor::registeredValue('group_member', [
                'user_id' => $session['user']['id']
            ])
        ) {
            return parent::handleError("GROUP_ERROR", "Sorry you're already in a group.");
        }

        $postData['user_id'] = $session['user']['id'];
        $created = Group::create($postData);
        if ($created > 0) {
            parent::handleError("GROUP_ERROR", "Group was created", "/index.php?page=group&id=$created");
            return true;
        }

        parent::handleError("GROUP_ERROR", "Group was not created. Try again later.");
        return false;
    }

    public static function updateGroup($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['name', 'group_id', 'description'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['name'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['description'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("GROUP_UPDATE", "An error occured, try again later.");
        }

        if (
            Group::isRegistered($postData['name'], $postData['group_id'])
        ) {
            return parent::handleError("GROUP_UPDATE", "Sorry there is already a group registered with this name.");
        }

        if (
            !DataProcessor::registeredValue('group_member', [
                'user_id' => $session['user']['id'],
                'role' => 'owner'
            ])
        ) {
            return parent::handleError("GROUP_UPDATE", "You are not authorized to edit this group.");
        }

        $updated = Group::update($postData['group_id'], [
            'name' => $postData['name'],
            'description' => $postData['description']
        ]);
        if ($updated > 0) {
            parent::handleError("GROUP_UPDATE", "Group was updated");
            return true;
        }

        parent::handleError("GROUP_UPDATE", "Group was not updated. Try again later.");
        return false;
    }
}