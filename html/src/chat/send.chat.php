<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/core/init.core.php";

$sender = DataProcessor::sanitizeData($_GET['s']);
$recipient = DataProcessor::sanitizeData($_GET['r']);
$body = $_GET['b'];

$chat = Chat::sendChat($sender, $recipient, $body);
