<?php
# Написано Мартан ван Версевелд #

class PageRenderer
{
    private static $incDir;
    private static $pagesPath;

    public static function initialize()
    {
        self::$incDir = $_SERVER['DOCUMENT_ROOT'] . "/src/inc";
        self::$pagesPath = $_SERVER['DOCUMENT_ROOT'] . "/pages";
    }

    public static function renderPage($page)
    {
        $page = htmlspecialchars(trim($page));

        $pagePath = self::$pagesPath . "/$page.php";
        $errorPath = $_SERVER['DOCUMENT_ROOT'] . "/src/errors/404.php";

        require_once self::$incDir . "/header.inc.php";
        require_once (file_exists($pagePath)) ? $pagePath : $errorPath;
        require_once self::$incDir . "/footer.inc.php";
    }
}

PageRenderer::initialize();
