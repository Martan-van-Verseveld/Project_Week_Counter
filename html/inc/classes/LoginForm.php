<?php
# Написано Мартан ван Версевелд #

class LoginForm {
    private $crud;
    private $data;
    private static $fields = ['email', 'password'];

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
        $this->data['email'] = sanitizeInput($this->data['email']);
        $this->data['password'] = sanitizeInput($this->data['password']);

        $this->validateData();

        $this->validateEmail();
        $this->validatePassword();

        $this->setupSession();

        $this->trigger_error("Logged in successfully!");
    }

    public function validateData() {
        if (empty($this->data['email']) || empty($this->data['password'])) {
            $this->trigger_error("Email or Password empty!");
        }
        if (strlen($this->data['email']) > 32 || strlen($this->data['password']) > 32) {
            $this->trigger_error("Email or password to long!");
        }
    }

    private function validateEmail() {
        $results = $this->crud->read('user', ['id'], [ 'email' => $this->data['email'] ]);
        if (empty($results[0])) {
            $this->trigger_error("Email or password incorrect.");
        }
    }

    private function validatePassword() {
        $results = $this->crud->read('user', ['password'], [ 'email' => $this->data['email'] ]);
        if (!password_verify($this->passwd_config['pepper'] . $this->data['password'] . $this->passwd_config['salt'], $results[0]['password'])) {
            $this->trigger_error("Email or password incorrect.");
        }
    }

    private function setupSession() {
        session_start();

        $results = $this->crud->read('user', ['email', 'firstname', 'lastname', 'class', 'role'], [ 
            'email' => $this->data['email'] 
        ]);

        foreach ($results[0] as $key => $value) {
            $_SESSION['USER_INFO'][$key] = $value;
        }
    }

    private function trigger_error($msg) {
        session_start();

        $_SESSION["FORM_ERROR"] = $msg;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}