<?php

require_once("Tag.php");

class NoAttributeFoundException extends Exception {}
class NoTagNameFoundException extends Exception {}

class Parsing {
  const re_unclosedTag = '/<([^\s]+)(.+)\/?>/';
  const re_attributePair = '/\s*([^=]+)="([^"]+)"/';

  public static function parseUnclosedTag($str) {
    $ret = preg_match(self::re_unclosedTag, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <input> elements");
    $tag_name = $matches[1];

    try {
      $tag_attributes = self::parseAttributes($matches[2]);
    } catch (NoAttributeFoundException $e) {
      //no attributes found
      $tag_attributes = array();
    }

    $tag = new Tag($tag_name, $tag_attributes);
    return $tag;
  }

  public static function parseAttributes($attr_str) {
    $ret = preg_match_all(self::re_attributePair, $attr_str, $matches);
    if($ret===false || $ret<=0) throw new NoAttributeFoundException("unable to find attributes");

    $attrs = array_combine($matches[1], $matches[2]);
    return $attrs;
  }
}

?>