<?php

class LoginHandler extends FormHandler
{
    private $data;

    public function handleForm($postData) {
        $postData = FormHandler::sanitizeData($postData);
        
        if (!FormHandler::validateFields(['email', 'password'], $postData)) {
            FormHandler::triggerError("Required fields not present!", "LOGIN_ERROR");
            return false;
        }

        if (!FormHandler::validateEmail($postData['email'])) {
            FormHandler::triggerError("EMail format not accepted!", "LOGIN_ERROR");
            return false;
        }
        if (!FormHandler::registeredEmail($postData['email'])) {
            FormHandler::triggerError("EMail or password incorrect!", "LOGIN_ERROR");
            return false;
        }
        if (!FormHandler::validatePassword($postData)) {
            FormHandler::triggerError("EMail or password incorrect!", "LOGIN_ERROR");
            return false;
        }


        FormHandler::triggerError("Successfully logged in!", "LOGIN_ERROR");
        return true;
    }
}