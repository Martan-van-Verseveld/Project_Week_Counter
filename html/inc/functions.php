<?php

<<<<<<< HEAD
$conn = require_once("db_config.php");

echo ($conn) ? "Connected!" : "Not Connected!";
=======
// Beautify print_r() output
function print_p($array) {
    echo "<pre>"; 
    print_r($array);
    echo "</pre>";
}

// Print true value
function print_tv($value) {
    $returnVal = (empty($value)) ? "null" : $value;

    echo "Value => " . $returnVal;
}
>>>>>>> 57aaada (first commit from vmware server)
