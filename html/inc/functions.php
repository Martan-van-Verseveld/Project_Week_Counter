<?php

//    Beautify outputs    \\
// print_r()
function print_p($array, $font_size = 1) {
    echo "<pre style='font-size: ". $font_size ."em;'>"; 
    print_r($array);
    echo "</pre>";
}

// print_r() more readable-ish (h = human)
function print_h($array, $font_size = 1) {
    echo "<pre style='font-size: ". $font_size ."em;'>"; 
    foreach ($array as $key => $value) echo "$key: $value\n"; 
    echo "</pre>";
}
