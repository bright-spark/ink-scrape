<?php

require_once("Tag.php");

class NoAttributeFoundException extends Exception {}
class NoTagNameFoundException extends Exception {}

class Parsing {
  const re_unclosedTag = '/<([^\s]+)(.+)\/?>/';
  const re_unclosedNamedTag = '/<%s(.+)\/?>/';
  const re_attributePair = '/\s*([^=]+)="([^"]+)"/';

  public static function parseAllUnclosedTags($str) {
    return self::parseAllUnclosedTagsRe($str, self::re_unclosedTag);
  }

  public static function parseAllNamedUnclosedTags($name, $str) {
    return self::parseAllUnclosedTagsRe($str, sprintf(self::re_unclosedNamedTag, $name));
  }

  protected static function parseAllUnclosedTagsRe($str, $re) {
    $ret = preg_match_all($re, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <input> elements");

    $tags = array();
    foreach($matches[0] as $tag_match) {
      array_push($tags, self::parseUnclosedTag($tag_match));
    }
    return $tags;
  }

  public static function parseNamedUnclosedTag($name, $str) {
    return self::parseUnclosedTagRe($str, sprintf(self::re_unclosedNamedTag, $name));
  }

  public static function parseUnclosedTag($str) {
    return self::parseUnclosedTagRe($str, self::re_unclosedTag);
  }

  protected static function parseUnclosedTagRe($str, $re) {
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