<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/core/init.core.php";

$sender = DataProcessor::sanitizeData($_GET['s']);
$recipient = DataProcessor::sanitizeData($_GET['r']);

$chat = Chat::getChat($sender, $recipient);

// $chat['body'] = htmlspecialchars_decode($chat['body']);

header('Content-Type: application/json');
echo json_encode($chat);