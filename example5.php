<?php

require_once 'inc/functions.php';
require_once 'class/JsonPath.php';

$content = new JsonPath('files/example5.json');

$output = $content->getJson();
display($output);
// try display($output);
// $output will be whole JSON

//$output = $content->jsonPath('//menu');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//menu/header');
// try display($output);
// $output will be a string

//$output = $content->jsonPath('//menu/items');
// try display($output);
// $output will be an array

//$output = $content->jsonPath('//menu/items[label="Open New"]');
// try display($output);
// $output will be an object