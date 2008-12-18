<?php

require_once("Tag.php");

class Parsing {
  public static function parseUnclosedTag($str) {
    $ret = preg_match('/<([^\s]+)(.+)>/', $str, $matches);
    if($ret===false || $ret<=0) throw new UnexpectedValueException("unable to find <input> elements");
    $tag_name = $matches[1];

    try {
      $tag_attributes = self::parseAttributes($matches[2]);
    } catch (UnexpectedValueException $e) {
      //no attributes found
      $tag_attributes = array();
    }

    $tag = new Tag($tag_name, $tag_attributes);
    return $tag;
  }

  public static function parseAttributes($attr_str) {
    $ret = preg_match_all('/\s*([^=]+)="([^"]+)"/', $attr_str, $matches);
    if($ret===false || $ret<=0) throw new UnexpectedValueException("unable to find attributes");

    $attrs = array_combine($matches[1], $matches[2]);
    return $attrs;
  }
}

?>