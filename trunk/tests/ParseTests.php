<?php

error_reporting(E_ALL | E_STRICT);

include("lib/Parsing.php");

$ref_attributes = array("name"=>"John Doe", "age"=>"20");

/**
 * Test parse-attributes
 * - test ability to parse attributes
 */
$passed = true;

$tag_attrs = Parsing::parseAttributes("name=\"{$ref_attributes["name"]}\"");
$passed = $passed && count($tag_attrs)===1 && array_key_exists("name", $tag_attrs) && ($tag_attrs["name"] == $ref_attributes["name"]);

$tag_attrs = Parsing::parseAttributes("name\n=\"{$ref_attributes["name"]}\"");
$passed = $passed && count($tag_attrs)===1 && array_key_exists("name", $tag_attrs) && ($tag_attrs["name"] == $ref_attributes["name"]);

$tag_attrs = Parsing::parseAttributes("name\n=  \"{$ref_attributes["name"]}\"");
$passed = $passed && count($tag_attrs)===1 && array_key_exists("name", $tag_attrs) && ($tag_attrs["name"] == $ref_attributes["name"]);

$tag_attrs = Parsing::parseAttributes("name\n=  \"{$ref_attributes["name"]}\" age=   \"{$ref_attributes["age"]}\"");
$passed = $passed && count($tag_attrs)==2 && array_key_exists("name", $tag_attrs) && array_key_exists("age", $tag_attrs) && ($tag_attrs["name"] == $ref_attributes["name"]) && ($tag_attrs["age"] == $ref_attributes["age"]);

echo(($passed ? 'passed' : 'failed')." test (parse-attributes)\n");

?>