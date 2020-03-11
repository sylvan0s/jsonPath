<?php

require_once 'inc/functions.php';
require_once 'class/JsonPath.php';

$content = new JsonPath('files/example.json');

$output = $content->getJson();
display($output);
// try display($output);
// $output will be whole JSON

//$output = $content->jsonPath('//web-app');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//web-app/servlet');
// try display($output);
// $output will be an array

//$output = $content->jsonPath('//web-app/servlet[servlet-name="cofaxCDS"]');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//web-app/servlet[servlet-name="cofaxCDS"]/init-param');
// try display($output);
// $output will be an object

//$output = $content->jsonPath('//web-app/servlet[servlet-name="cofaxCDS"]/init-param/templatePath');
// try display($output);
// $output will be a string

//$output = $content->jsonPath('//web-app/servlet[servlet-name="cofaxCDS"]/init-param/useJSP');
// try display($output);
// $output will be a boolean

//$output = $content->jsonPath('//web-app/servlet[servlet-name="cofaxCDS"]/init-param/cachePackageTagsTrack');
// try display($output);
// $output will be an integer

//$output = $content->jsonPath('//web-app/servlet[servlet-name="cofaxCDS"]/init-param/configGlossary:poweredBy');
// try display($output);
// $output will be a string