<?php
# Написано Мартан ван Версевелд #


//    Beautify outputs    \\
// print_r()
function print_p($array, $tags = '', $font_size = 1) {
    echo "$tags<pre style='font-size: ". $font_size ."em;'>"; 
    print_r($array);
    echo "</pre>$tags";
}

// print_r() more readable-ish (h = human)
function print_h($array, $font_size = 1) {
    echo "<pre style='font-size: ". $font_size ."em;'>"; 
    foreach ($array as $key => $value) echo "$key: $value\n"; 
    echo "</pre>";
}

// QOL functions
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}


// Form
function print_formError($error) {
    echo "
        <span id='error'>". $error ."</span>
    ";
}
