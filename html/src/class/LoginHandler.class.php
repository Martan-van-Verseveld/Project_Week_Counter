<?php

class LoginHandler
{
    public static function processForm($postData): bool
    {
        $postData = DataProcessor::sanitizeData($postData);

        if (
            !DataProcessor::validateFields($postData, ['email', 'password'])
            || !DataProcessor::validateType([$postData['email'] => FILTER_VALIDATE_EMAIL])
            || !DataProcessor::validateType([$postData['password'] => FILTER_VALIDATE_REGEXP])
        ) {
            return self::handleError("An error occured, try again later.");
        }

        if (
            !DataProcessor::registeredValue('user', ['email' => $postData['email']])
            || !self::verifyPassword($postData)
        ) {
            return self::handleError("EMail or password incorrect.");
        }

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user'] = self::setSession($postData['email']);

        self::handleError("Logged in.", "/index.php?page=home");
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

    private static function handleError($msg, $location = null): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION["ERROR"]["LOGIN_ERROR"] = $msg;

        $location = ($location != null) ? $location : $_SERVER['HTTP_REFERER'];
        Redirect::to($location);
        return false;
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