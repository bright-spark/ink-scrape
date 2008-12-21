<?php

error_reporting(E_ALL | E_STRICT);

include("lib/Parsing.php");

$ref_attributes = array("name"=>"John Doe", "age"=>"20");
$ref_doc = <<<EOD
<person></person>
<person/>
<person    />
<person name="{$ref_attributes["name"]}"/>
<person name="{$ref_attributes["name"]}"          />

<persons/>
<persons       />
<persons></person>
EOD;

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

/**
 * Test parse-unclosed-named-tags
 */
$passed = true;

$tags = Parsing::parseAllUnclosedTags($ref_doc, "person");
$passed = $passed && count($tags)==5;

//start at index 0, for 3 items
for($i=0;$i<3;$i++) {
  $tag_attrs = $tags[$i]->attributes();
  $passed = $passed && array_key_exists($i, $tags) && empty($tag_attrs);
}

//start at index 3, for 2 items
for($i=3;$i<2+3;$i++) {
  $tag_attrs = $tags[$i]->attributes();
  $passed = $passed && array_key_exists($i, $tags) && !empty($tag_attrs) && array_key_exists("name", $tag_attrs) && ($tag_attrs["name"] == $ref_attributes["name"]);
}

echo(($passed ? 'passed' : 'failed')." test (parse-unclosed-named-tags)\n");

?>