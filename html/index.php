<?php
# Написано Мартан ван Версевелд #

require_once "{$_SERVER['DOCUMENT_ROOT']}/src/core/init.core.php";

if (!isset($_GET['page']) || empty($_GET['page'])) {
    Redirect::to('index.php?page=home');
    exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();

PageRenderer::renderPage(sanitizeInput($_GET['page']));

if (Session::get('REFERER') != $_SERVER['REQUEST_URI']) {
    Session::put('REFERER', $_SERVER['REQUEST_URI']);
}

// print_p($_SESSION);
