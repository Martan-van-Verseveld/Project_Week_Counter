<?php

class SchoolClassHandler extends Handler
{
    public static function createClass($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['name'])
            || !DataProcessor::validateFields($session['user'], ['id', 'role'])
            || !DataProcessor::validateType([$postData['name'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("CLASS_CREATE_ERROR", "An error occured, try again later.");
        }

        if (
            DataProcessor::registeredValue('class', ['name' => $postData['name']])
        ) {
            return parent::handleError("CLASS_CREATE_ERROR", "Sorry there is already a class registered with this name.");
        }
        
        $postData['user_id'] = $session['user']['id'];
        $created = SchoolClass::create($postData);
        if ($created > 0) {
            parent::handleError("CLASS_CREATE_ERROR", "Class was created", "/index.php?page=class&id=$created");
            return true;
        }

        parent::handleError("CLASS_CREATE_ERROR", "Class was not created. Try again later.");
        return false;
    }

    public static function editClass($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['name', 'class_id'])
            || !DataProcessor::validateFields($session['user'], ['id', 'role'])
            || !DataProcessor::validateType([$postData['name'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("CLASS_EDIT_ERROR", "An error occured, try again later.");
        }

        if (
            SchoolClass::isRegistered($postData['name'], $postData['class_id'])
        ) {
            return parent::handleError("CLASS_EDIT_ERROR", "Sorry there is already a class registered with this name.");
        }
        

        $updated = SchoolClass::update($postData['class_id'], [
            'name' => $postData['name']
        ]);
        if ($updated > 0) {
            parent::handleError("CLASS_EDIT_ERROR", "Class was updated", "/index.php?page=class&id={$postData['class_id']}");
            return true;
        }

        parent::handleError("CLASS_EDIT_ERROR", "Class was not updated. Try again later.");
        return false;
    }
}