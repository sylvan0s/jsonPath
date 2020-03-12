<?php

require_once 'inc/functions.php';
require_once 'class/JsonPath.php';

$content = new JsonPath('files/example3.json');

$output = $content->getJson();
display($output);
// try display($output);
// $output will be whole JSON

//$output = $content->jsonPath('//menu');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//menu/id');
// try display($output);
// $output will be a string

//$output = $content->jsonPath('//menu/popup');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//menu/popup/menuitem');
// try display($output);
// $output will be an array

//$output = $content->jsonPath('//menu/popup/menuitem[0]');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//menu/popup/menuitem[0]/value');
// try display($output);
// $output will be a string