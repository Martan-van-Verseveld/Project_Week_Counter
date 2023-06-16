<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/core/init.core.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Redirect::to($_SERVER['HTTP_REFERER']);
    return;
}


if ($_POST['action'] == "register") {
    $returned = RegistrationHandler::processForm($_POST);
    if (!$returned) {
        Redirect::to('/index.php?page=login');
    }
    return;
}

if ($_POST['action'] == "login") {
    $returned = LoginHandler::processForm($_POST);
    if (!$returned) {
        Redirect::to('/index.php?page=home');
    }
    return;
}

if ($_POST['action'] == "request") {
    if (session_status() === PHP_SESSION_NONE) session_start();
    RequestHandler::processRequest($_POST, $_SESSION);
    return;
}

if ($_POST['action'] == "request_decline") {
    if (session_status() === PHP_SESSION_NONE) session_start();
    RequestHandler::declineRequest($_POST, $_SESSION);
    return;
}

if ($_POST['action'] == "request_accept") {
    if (session_status() === PHP_SESSION_NONE) session_start();
    RequestHandler::acceptRequest($_POST, $_SESSION);
    return;
}

if ($_POST['action'] == "member_remove") {
    if (session_status() === PHP_SESSION_NONE) session_start();
    MemberHandler::removeMember($_POST, $_SESSION);
    return;
}