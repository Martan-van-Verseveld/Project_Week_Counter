<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");

class page
{
    public $name;
    public $path;

    public function __construct($requested_page) {
        // Set variables
        $this->name = $requested_page;
        $this->path = $_SERVER['DOCUMENT_ROOT'] . "/pages/$requested_page.php";
    }

    public function load($debug = false) {
        // Enable errors
        ini_set('display_errors', $debug);

        // Include page
        include($this->path);

        // Disable errors
        ini_set('display_errors', 0);
    }

    public function load_full($debug = false) {
        // Enable errors
        ini_set('display_errors', $debug);

        // Include and Require header + page + footer
        require($_SERVER['DOCUMENT_ROOT'] . "/inc/header.php");
        include($this->path);
        require($_SERVER['DOCUMENT_ROOT'] . "/inc/footer.php");

        // Disable errors
        ini_set('display_errors', 0);
    }

    public function index($beautify = true) {
        // Put in temporary array
        $array = [
            'name' => $this->name,
            'path' => $this->path
        ];

        // Display Array
        ($beautify) ? print_p($array) : print_r($array);
    }
}