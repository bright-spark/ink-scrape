<?php

require_once("Tag.php");

class NoAttributeFoundException extends Exception {}
class NoTagNameFoundException extends Exception {}

class Parsing {
  const re_unclosedTagGeneral = '/<[^>]+\/?>/';
  const re_unclosedTagNamedGeneral = '/<%s[^>]+\/?>/';
  const re_unclosedTag = '/<([^\s]+)(.+)\/?>/';
  const re_unclosedNamedTag = '/<%s(.+)\/?>/';
  const re_attributePair = '/\s*([^=]+)="([^"]+)"/';

  public static function parseAllUnclosedTags($str) {
    return self::__parseAllUnclosedNamedTags($str);
  }

  public static function parseAllNamedUnclosedTags($name, $str) {
    return self::__parseAllUnclosedNamedTags($str, $name);
  }

  protected static function __parseAllUnclosedNamedTags($str, $name=null) {
    $re = !is_string($name) ? self::re_unclosedTagGeneral : sprintf(self::re_unclosedTagNamedGeneral, $name);

    $ret = preg_match_all($re, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <".(empty($name) ? 'tag' : $name)."> elements");

    $tags = array();
    foreach($matches[0] as $tag_match) {
      array_push($tags, self::parseNamedUnclosedTag($name, $tag_match));
    }
    return $tags;
  }

  public static function parseNamedUnclosedTag($name, $str) {
    return self::__parseUnclosedTagNamed($str, $name);
  }

  public static function parseUnclosedTag($str) {
    return self::__parseUnclosedTagNamed($str);
  }

  protected static function __parseUnclosedTagNamed($str, $name=null) {
    $re = !is_string($name) ? re_unclosedTag : sprintf(self::re_unclosedNamedTag, $name);

    $ret = preg_match($re, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <".(empty($name) ? 'tag' : $name)."> elements");
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