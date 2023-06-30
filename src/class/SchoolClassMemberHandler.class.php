<?php

class SchoolClassMemberHandler extends Handler
{    
    public static function addMember($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['user_id', 'class_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['class_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("CLASS_MEMBER_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('class', ['id' => $postData['class_id']])
            || !DataProcessor::registeredValue('user', ['id' => $postData['user_id']])
        ) {
            return parent::handleError("CLASS_MEMBER_ERROR", "Class or user does not exist...");
        }

        if ($session['user']['role'] != 'teacher') {
            return parent::handleError("CLASS_MEMBER_ERROR", "You do not have the authorization to add users to a class.");
        }

        $add = SchoolClassMember::create([
            'class_id' => $postData['class_id'],
            'user_id' => $postData['user_id']
        ]);
        if ($add) {
            parent::handleError("CLASS_MEMBER_ERROR", "Member added!");
            return true;
        }

        return parent::handleError("CLASS_MEMBER_ERROR", "Member was not added, try again later.!");
    }

    public static function removeMember($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['user_id', 'class_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['class_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("CLASS_MEMBER_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('class', ['id' => $postData['class_id']])
            || !DataProcessor::registeredValue('user', ['id' => $postData['user_id']])
        ) {
            return parent::handleError("CLASS_MEMBER_ERROR", "Class or user does not exist...");
        }

        if ($session['user']['role'] != 'teacher') {
            return parent::handleError("CLASS_MEMBER_ERROR", "You do not have the authorization to remove users from a group.");
        }

        $delete = SchoolClassMember::delete([
            'class_id' => $postData['class_id'],
            'user_id' => $postData['user_id']
        ]);
        if ($delete) {
            parent::handleError("CLASS_MEMBER_ERROR", "Member removed!");
            return true;
        }

        return parent::handleError("CLASS_MEMBER_ERROR", "Member was not removed, try again later.!");
    }
}
