<?php

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $baseDir = "{$_SERVER['DOCUMENT_ROOT']}/src/class";
    $extension = ".class.php";
    $fullPath = "$baseDir/$class$extension";

    if (file_exists($fullPath)) {
        include $fullPath;
    }
});