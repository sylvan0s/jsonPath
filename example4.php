<?php

require_once 'inc/functions.php';
require_once 'class/JsonPath.php';

$content = new JsonPath('files/example4.json');

$output = $content->getJson();
display($output);
// try display($output);
// $output will be whole JSON

//$output = $content->jsonPath('//widget');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//widget/debug');
// try display($output);
// $output will be a string

//$output = $content->jsonPath('//widget/window');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//widget/window/title');
// try display($output);
// $output will be a string