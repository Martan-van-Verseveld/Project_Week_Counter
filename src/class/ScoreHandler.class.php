<?php

class ScoreHandler extends Handler
{
    public function updateScore($postData, $session) 
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['group_id', 'score'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['score'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("SCORE_EDIT_ERROR", "An error occured, try again later.");
        }

        if ($session['user']['role'] != 'teacher') {
            return parent::handleError("SCORE_EDIT_ERROR", "You are not authorized to create themes.");
        }

        if (Score::update($postData['group_id'], [
            'score' => $postData['score']
        ])) {
            $members = Group::getGroupMembers($postData['group_id']);
            foreach ($members as $member) {
                Inbox::create($member['id'], "Score update", "Your group's score was updated to {$postData['score']}");
            }

            parent::handleError("SCORE_EDIT_ERROR", "Score has been updated.", "/index.php?page=scores");
            return true;
        }
        
        return parent::handleError("SCORE_EDIT_ERROR", "Score was not updated. Try again later.");
    }
}