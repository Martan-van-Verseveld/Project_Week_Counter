<?php

class FeedbackHandler extends Handler
{
    public static function createFeedback($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['id', 'title', 'description'])
            || !DataProcessor::validateFields($session['user'], ['id', 'role'])
            || !DataProcessor::validateType([$postData['id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['title'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("GROUP_ERROR", "An error occured, try again later.");
        }

        switch ($postData['type']) {
            case "group":
                if (!DataProcessor::registeredValue('group_info', ['id' => $postData['id']])) {
                    return parent::handleError("GROUP_ERROR", "Sorry this {$postData['type']} doesn't exist.");
                }
                break;

            case "user":
                if (!DataProcessor::registeredValue('user', ['id' => $postData['id']])) {
                    return parent::handleError("GROUP_ERROR", "Sorry this {$postData['type']} doesn't exist.");
                }
                break;

            default:
                return parent::handleError("GROUP_ERROR", "Sorry this {$postData['type']} doesn't exist.");
        }

        if (!DataProcessor::registeredValue('user', [
            'id' => $session['user']['id'],
            'role' => 'teacher'
        ])) {
            return parent::handleError("GROUP_ERROR", "This user is not a teacher.");
        }

        if (Feedback::create($postData['type'], [
            'id' => $postData['id'],
            'teacher_id' => $session['user']['id'],
            'title' => $postData['title'],
            'description' => $postData['description']
        ]) > 0) {
            parent::handleError("GROUP_ERROR", "Feedback was created");
            return true;
        }

        parent::handleError("GROUP_ERROR", "Feedback was not created. Try again later.");
        return false;
    }
}