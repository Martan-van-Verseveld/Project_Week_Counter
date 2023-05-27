<?php
# Написано Мартан ван Версевелд #

class PageRenderer
{
    private $incDir;
    private $pagesPath;

    public function __construct($pagesPath)
    {
        $this->incDir = $_SERVER['DOCUMENT_ROOT'] . "/src/inc";
        $this->pagesPath = $_SERVER['DOCUMENT_ROOT'] . $pagesPath;
    }

    public function renderPage($page)
    {
        $page = htmlspecialchars(trim($page));

        $pagePath = "{$this->pagesPath}/$page.php";
        $errorPath = "{$this->pagesPath}/404.php";

        require_once "{$this->incDir}/header.inc.php";
        require_once (file_exists($pagePath)) ? $pagePath : $errorPath;
        require_once "{$this->incDir}/footer.inc.php";
    }
}
