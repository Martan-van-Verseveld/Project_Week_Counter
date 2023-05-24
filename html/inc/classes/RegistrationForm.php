<?php
# Написано Мартан ван Версевелд #

class RegistrationForm {
    private $crud;
    private $data;
    private static $fields = ['email', 'firstname', 'lastname', 'role', 'password', 'password_conf'];

    private $passwd_config = [
        'salt' => "4b58e936d051dd2ad039ef12d5c0174f",
        'pepper' => "1166148776b0476d7a3a60be63d31ae4",
        'encryption' => CRYPT_SHA256
    ];

    public function __construct($crud, $post_data) {
        $this->crud = $crud;
        $this->data = $post_data;
    }

    public function processForm() {
        // Setting variables
        $this->data['email'] = sanitizeInput($this->data['email']);
        $this->data['firstname'] = sanitizeInput($this->data['firstname']);
        $this->data['lastname'] = sanitizeInput($this->data['lastname']);
        $this->data['role'] = sanitizeInput($this->data['role']);
        $this->data['password'] = sanitizeInput($this->data['password']);
        $this->data['password_conf'] = sanitizeInput($this->data['password_conf']);

        $this->validateData();

        $this->validateEmail();
        $this->validatePassword();

        $this->insertData();

        $this->trigger_error("Account registered!");
    }

    public function validateData() {
        foreach(self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                $this->trigger_error("$field value not present!");
            }
        }
        if (empty($this->data['email']) || empty($this->data['password'])) {
            $this->trigger_error("Email or password empty!");
        }
        if (strlen($this->data['email']) > 32 || strlen($this->data['password']) > 32) {
            $this->trigger_error("Email or password to long!");
        }
    }

    private function validateEmail() {
        $results = $this->crud->read('user', '*', [ 'email' => $this->data['email'] ]);
        if (!empty($results[0])) {
            $this->trigger_error("Email is already registered");
        }
    }

    private function validatePassword() {
        if ($this->data['password'] != $this->data['password_conf']) {
            $this->trigger_error("Passwords don't match!");
        }
    }

    private function insertData() {
        $hashed_passwd = password_hash($this->passwd_config['pepper'] . $this->data['password'] . $this->passwd_config['salt'], $this->passwd_config['encryption']);

        // Insert record into database
        $insert = $this->crud->create('user', [
            'email' => $this->data['email'],
            'firstname' => $this->data['firstname'],
            'lastname' => $this->data['lastname'],
            'role' => $this->data['role'],
            'password' => $hashed_passwd
        ]);

        if (!$insert) $this->trigger_error("Failed to insert record. Please, try again later!");
    }

    private function trigger_error($msg) {
        session_start();

        $_SESSION["FORM_ERROR"] = $msg;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}