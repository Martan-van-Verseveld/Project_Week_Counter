<?php

class ThemeHandler extends Handler
{
    public function createTheme($postData, $session) 
    {
        $themeDescription = $postData['description'];
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['title', 'description'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['title'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("THEME_CREATE_ERROR", "An error occured, try again later.");
        }

        if (!DataProcessor::registeredValue('user', [
            'id' => $session['user']['id'],
            'role' => 'teacher'
        ])) {
            return parent::handleError("THEME_CREATE_ERROR", "You are not authorized to create themes.");
        }

        if (DataProcessor::registeredValue('theme', [
            'title' => $postData['title']
        ])) {
            return parent::handleError("THEME_CREATE_ERROR", "There is already a theme with this name.");
        }

        $createdId = Theme::create($postData['title'], $themeDescription);
        if ($createdId > 0) {
            parent::handleError("THEME_CREATE_ERROR", "Theme has been created.", "/index.php?page=theme&id=$createdId");
            return true;
        }
        
        return parent::handleError("THEME_CREATE_ERROR", "Theme was not created. Try again later.");
    }

    public function updateTheme($postData, $session) 
    {
        $themeDescription = $postData['description'];
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['theme_id', 'title', 'description', 'max'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['theme_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['title'] => FILTER_VALIDATE_REGEXP])
            || !DataProcessor::validateType([$postData['max'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("THEME_UPDATE_ERROR", "An error occured, try again later.");
        }

        if (!DataProcessor::registeredValue('user', [
            'id' => $session['user']['id'],
            'role' => 'teacher'
        ])) {
            return parent::handleError("THEME_UPDATE_ERROR", "You are not authorized to create themes.");
        }

        if (Group::isRegistered($postData['title'], $postData['theme_id'])) {
            return parent::handleError("THEME_UPDATE_ERROR", "There is already a theme with this name.");
        }

        $theme = Theme::getTheme($postData['theme_id']);
        if ($theme['regiser_count'] > $postData['max'] && $theme['active']) {
            return parent::handleError("THEME_UPDATE_ERROR", "You can't shrink the spots that far down while the theme is activated.");
        }

        $updated = Theme::update($postData['theme_id'], [
            'title' => $postData['title'], 
            'description' => $themeDescription, 
            'max' => $postData['max'], 
            'active' => $postData['active'], 
            'hidden' => $postData['hidden']
        ]);
        if ($updated) {
            parent::handleError("THEME_UPDATE_ERROR", "Theme has been updated.", "/index.php?page=theme&id={$theme['id']}");
            return true;
        }
        
        return parent::handleError("THEME_UPDATE_ERROR", "Theme was not updated. Try again later.");
    }

    public function registerTheme($postData, $session)
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['theme_id', 'group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['theme_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("THEME_REGISTER_ERROR", "An error occured, try again later.");
        }

        if (!DataProcessor::registeredValue('group_member', [
            'user_id' => $session['user']['id'],
            'group_id' => $postData['group_id'],
            'role' => 'owner'
        ])) {
            return parent::handleError("THEME_REGISTER_ERROR", "You are not authorized to register for themes.");
        }
        
        if (DataProcessor::registeredValue('group_theme', [
            'group_id' => $postData['group_id']
        ])) {
            return parent::handleError("THEME_REGISTER_ERROR", "You've already joined a theme.");
        }

        if (Theme::registerGroup($postData['theme_id'], $postData['group_id'])) {
            parent::handleError("THEME_REGISTER_ERROR", "Theme has been registerd.", "/index.php?page=group&id={$postData['group_id']}");
            return true;
        }
        
        return parent::handleError("THEME_REGISTER_ERROR", "Theme was not registered. Try again later.");
    }

    public function leaveTheme($postData, $session)
    {
        $postData = DataProcessor::sanitizeData($postData);
        $session = DataProcessor::sanitizeData($session);

        if (
            !DataProcessor::validateFields($postData, ['theme_id', 'group_id'])
            || !DataProcessor::validateFields($session['user'], ['id'])
            || !DataProcessor::validateType([$postData['theme_id'] => FILTER_VALIDATE_INT])
            || !DataProcessor::validateType([$postData['group_id'] => FILTER_VALIDATE_INT])
        ) {
            return parent::handleError("THEME_LEAVE_ERROR", "An error occured, try again later.");
        }

        if (!DataProcessor::registeredValue('group_member', [
            'user_id' => $session['user']['id'],
            'group_id' => $postData['group_id'],
            'role' => 'owner'
        ])) {
            return parent::handleError("THEME_LEAVE_ERROR", "You are not authorized to leave themes.");
        }

        if (Theme::isActive($postData['theme_id'])) {
            return parent::handleError("THEME_LEAVE_ERROR", "You cannot leave active themes.");
        }

        if (Theme::deleteGroup($postData['group_id'])) {
            parent::handleError("THEME_LEAVE_ERROR", "Theme left.", "/index.php?page=themes");
            return true;
        }
        
        return parent::handleError("THEME_LEAVE_ERROR", "Theme was not discarded. Try again later.");
    }
}