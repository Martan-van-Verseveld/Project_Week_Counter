<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/core/init.core.php";
if (session_status() === PHP_SESSION_NONE) session_start();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Redirect::to($_SERVER['HTTP_REFERER']);
    return;
}

switch ($_POST['action']) {
    // User
    case "register":
        $returned = RegistrationHandler::processForm($_POST);
        if (!$returned) {
            Redirect::to('/index.php?page=login');
        }
        break;

    case "login":
        $returned = LoginHandler::processForm($_POST);
        if (!$returned) {
            Redirect::to('/index.php?page=home');
        }
        break;

    case "password-update":
        UserHandler::updatePassword($_POST, $_SESSION);
        break;

    // Requests
    case "request":
        RequestHandler::processRequest($_POST, $_SESSION);
        break;
    
    case "request-decline":
        RequestHandler::declineRequest($_POST, $_SESSION);
        break;

    case "request-accept":
        RequestHandler::acceptRequest($_POST, $_SESSION);
        break;

    // Members
    case "member-remove":
        MemberHandler::removeMember($_POST, $_SESSION);
        break;

    case "member-owner":
        MemberHandler::updateOwnership($_POST, $_SESSION);
        break;

    case "member-leave":
        MemberHandler::leaveMember($_POST, $_SESSION);
        break;

    // Invites
    case "invite-user":
        RequestHandler::inviteRequest($_POST, $_SESSION);
        break;

    case "invite-accept":
        RequestHandler::inviteAccept($_POST, $_SESSION);
        break;

    case "invite-decline":
        RequestHandler::inviteDecline($_POST, $_SESSION);
        break;

    // Group
    case "group-create":
        GroupHandler::registerGroup($_POST, $_SESSION);
        break;
        
    case "group-edit":
        GroupHandler::updateGroup($_POST, $_SESSION);
        break;

    // Settings
    case "settings-update":
        SettingsHandler::updateSettings($_POST, $_SESSION);
        break;

    // Profile
    case "profile-edit":
        UserHandler::updateProfile($_POST, $_SESSION);
        break;
}