<?php

function display($obj, $isDie = false) {
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';

    if ($isDie)
        die;
}