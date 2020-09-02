<?php

// My own custom dump, working on browsers
if (!function_exists("display")) {
    function display($obj, $isDie = false) {
        echo '<pre>';
        var_dump($obj);
        echo '</pre>';

        if ($isDie) {
            die;
        }
    }
}

if (!function_exists('my_microtime')) {
    function my_microtime() {
        return round(microtime(true) * 1000);
    }
}

// For PHP <= 7.3.0 https://www.php.net/manual/en/function.array-key-last.php#123016
if (!function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array) - 1];
    }
}

// For PHP <= 7.3.0 https://www.php.net/manual/en/function.array-key-first.php#refsect1-function.array-key-first-notes
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }

        return NULL;
    }
}