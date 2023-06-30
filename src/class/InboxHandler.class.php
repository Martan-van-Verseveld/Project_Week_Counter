<?php

class InboxHandler extends Handler
{
    public function deleteInbox($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['inbox_id', 'user_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['inbox_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['user_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("INBOX_ERROR", "An error occured, try again later.");
        }

        if (
            $postData['user_id'] != $session['user']['id']
        ) {
            return parent::handleError("INBOX_ERROR", "You can not delete other's inbox messages.");
        }

        if (Inbox::delete($postData['inbox_id'])) {
            parent::handleError("INBOX_ERROR", "Inbox message deleted.");
            return true;
        }

        parent::handleError("INBOX_ERROR", "Inbox message was not deleted. Try again later.");
        return false;
    }
}