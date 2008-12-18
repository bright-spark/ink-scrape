<?php

require_once("Tag.php");

class Parsing {
  public static function parseAttributes($attr_str) {
    $ret = preg_match_all('/\s*([^=]+)="([^"]+)"/', $attr_str, $matches);
    if($ret===false || $ret<=0) throw new UnexpectedValueException("unable to find attributes");

    $attrs = array_combine($matches[1], $matches[2]);
    return $attrs;
  }
}

?>