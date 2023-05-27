<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/class/UserInfo.class.php";

class RegistrationHandler extends FormHandler
{
    private $data;

    public function handleForm($postData) {
        $postData = FormHandler::sanitizeData($postData);
        
        if (!FormHandler::validateFields(['email', 'firstname', 'lastname', 'password', 'password_conf'], $postData)) {
            FormHandler::triggerError("Required fields not present!", "REGISTER_ERROR");
            return false;
        }
        
        if (!FormHandler::validateEmail($postData['email'])) {
            FormHandler::triggerError("EMail format invalid!", "REGISTER_ERROR");
            return false;
        }
        if (FormHandler::registeredEmail($postData['email'])) {
            FormHandler::triggerError("EMail already registered!", "REGISTER_ERROR");
            return false;
        }
        if (!FormHandler::comparePasswords($postData['password'], $postData['password_conf'])) {
            FormHandler::triggerError("Passwords don't match, try again!", "REGISTER_ERROR");
            return false;
        }

        $userInfo = new UserInfo;
        if (!$userInfo->createUser($postData)) {
            FormHandler::triggerError("Record was not inserted, try again later!", "REGISTER_ERROR");
            return false;
        }


        FormHandler::triggerError("Account registered!", "REGISTER_ERROR");
        return true;
    }
}