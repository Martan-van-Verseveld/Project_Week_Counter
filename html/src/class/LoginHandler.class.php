<?php

class LoginHandler extends Handler
{
    public static function processForm($postData): bool
    {
        $postData = DataProcessor::sanitizeData($postData);

        if (
            !DataProcessor::validateFields($postData, ['email', 'password'])
            || !DataProcessor::validateType([$postData['email'] => FILTER_VALIDATE_EMAIL])
            || !DataProcessor::validateType([$postData['password'] => FILTER_VALIDATE_REGEXP])
        ) {
            return parent::handleError("LOGIN_ERROR", "An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('user', ['email' => $postData['email']])
            || !self::verifyPassword($postData)
        ) {
            return parent::handleError("LOGIN_ERROR", "EMail or password incorrect.");
        }

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user'] = self::setSession($postData['email']);

        parent::handleError("LOGIN_ERROR", "Logged in.", "/index.php?page=home");
        return true;
    }

    protected static function verifyPassword($data): bool
    {
        $pdo = Dbh::getConnection();

        $query = "
            SELECT `user`.password
            FROM `user`
            WHERE `user`.email = :email;
        ";

        $sto = $pdo->prepare($query);
        $sto->execute([
            ':email' => $data['email']
        ]);
        $results = $sto->fetch(PDO::FETCH_ASSOC);

        $passed = password_verify(PASS_PEPPER . $data['password'] . PASS_SALT, $results['password']);
        return $passed;
    }

    private static function setSession($userEmail): array
    {
        $pdo = Dbh::getConnection();

        $query = "
            SELECT `user`.*
            FROM `user`
            WHERE `user`.email = :email;
        ";

        $sto = $pdo->prepare($query);
        $sto->execute([
            ':email' => $userEmail
        ]);
        $fetch = $sto->fetch(PDO::FETCH_ASSOC);

        $results = [
            'id' => $fetch['id'],
            'email' => $fetch['email'],
            'firstname' => $fetch['firstname'],
            'lastname' => $fetch['lastname'],
            'role' => $fetch['role']
        ];

        return $results;
    }
}