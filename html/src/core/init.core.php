<?php
# Написано Мартан ван Версевелд #

# Database Configuration
DEFINE('DB_HOST', '127.0.0.1');
DEFINE('DB_PORT', '3306');
DEFINE('DB_NAME', 'pw_counter');
DEFINE('DB_USER', 'pw_counter');
DEFINE('DB_PASS', 'YU@aHb01j6[UKXlu');

# Password Configuration
DEFINE('PASS_ENC', CRYPT_SHA256);
DEFINE('PASS_SALT', '4b58e936d051dd2ad039ef12d5c0174f');
DEFINE('PASS_PEPPER', '1166148776b0476d7a3a60be63d31ae4');

# Debugging
DEFINE('PDO_DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Files
require_once "{$_SERVER['DOCUMENT_ROOT']}/src/inc/functions.inc.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/src/inc/class_autoloader.inc.php";

# Session
(session_status() == 0) ? session_start() : null;
$_SESSION['REFERER'] = '';
