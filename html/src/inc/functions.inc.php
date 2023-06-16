<?php
# Написано Мартан ван Версевелд #


// Beautified print_r()
function print_p($array, $tags = '', $font_size = 1) {
    echo "$tags<pre style='font-size: ". $font_size ."em;'>"; 
    print_r($array);
    echo "</pre>$tags";
}


// QOL functions
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}


// Testing
function timeCode($function) {
    $bt = microtime(true);

    // Code to test
    $function();

    $et = microtime(true);
    echo "\nCompleted in ". ($et - $bt) ." microseconds.";
}