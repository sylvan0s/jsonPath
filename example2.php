<?php


require_once 'inc/functions.php';
require_once 'class/JsonPath.php';

$content = new JsonPath('files/example2.json');

$output = $content->getJson();
display($output);
// try display($output);
// $output will be whole JSON

//$output = $content->jsonPath('//glossary');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//glossary/title');
// try display($output);
// $output will be a string

//$output = $content->jsonPath('//glossary/GlossDiv');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//glossary/GlossDiv/GlossList/GlossEntry');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//glossary/GlossDiv/GlossList/GlossEntry/GlossDef/para');
// try display($output);
// $output will be a string

//$output = $content->jsonPath('//glossary/GlossDiv/GlossList/GlossEntry/GlossDef/GlossSeeAlso');
// try display($output);
// $output will be an array