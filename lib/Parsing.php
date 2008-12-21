<?php

require_once("Tag.php");

class NoAttributeFoundException extends Exception {}
class NoTagNameFoundException extends Exception {}

class Parsing {
  //unclosed tags
  const re_unclosedTag = '/<([^\s\/>]+)(?:(\s+[^>]*?)\/?>|\/?>)/';
  const re_unclosedNamedTag = '/<(%s)(?:(\s+[^>]*?)\/?>|\/?>)/';

  //closed tags
  const re_closedNamedTags = '/<%s([^>]*)(?!\/)>(.*?)<\/%s>/s';

  //attributes
  const re_attributePair = '/\s*([^=\s]+)\s*=\s*"([^"]+)"/';

  public static function parseAllUnclosedTags($str, $name=null) {
    $re = !is_string($name) ? self::re_unclosedTag : sprintf(self::re_unclosedNamedTag, $name);

    $ret = preg_match_all($re, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <".(empty($name) ? 'tag' : $name)."> elements");

    $tags = array();
    foreach($matches[0] as $tag_match) {
      array_push($tags, self::parseUnclosedTag($tag_match, $name));
    }
    return $tags;
  }

  public static function parseUnclosedTag($str, $name=null) {
    $re = !is_string($name) ? re_unclosedTag : sprintf(self::re_unclosedNamedTag, $name);

    $ret = preg_match($re, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <".(empty($name) ? 'tag' : $name)."> elements");
    $tag_name = $matches[1];

    $tag_attributes = null;
    if(array_key_exists(2, $matches)) {
      try {
        $tag_attributes = self::parseAttributes($matches[2]);
      } catch (NoAttributeFoundException $e) {
        //no attributes found
      }
    }

    $tag = new Tag($tag_name, $tag_attributes);
    return $tag;
  }

  public static function parseAllClosedNamedTags($str, $name) {
    $re = sprintf(self::re_closedNamedTags, $name, $name);

    $ret = preg_match_all($re, $str, $matches);
    if($ret===false || $ret<=0) throw new NoTagNameFoundException("unable to find <".$name."> elements");

    $tag_count = count($matches[0]);
    $tags = array();
    for($i=0;$i<$tag_count;$i++) {
      try {
        $tag_attributes = self::parseAttributes($matches[1][$i]);
      } catch (NoAttributeFoundException $e) {
        //no attributes found
        $tag_attributes = null;
      }
      $tag = new Tag($name, $tag_attributes, $matches[2][$i]);
      array_push($tags, $tag);
    }

    return $tags;
  }

  public static function parseAttributes($attr_str) {
    $ret = preg_match_all(self::re_attributePair, $attr_str, $matches);
    if($ret===false || $ret<=0) throw new NoAttributeFoundException("unable to find attributes");

    $attrs = array_combine($matches[1], $matches[2]);
    return $attrs;
  }
}

?>