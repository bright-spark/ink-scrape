<?php

class InkScrape {
  // create an pseudo-anonymous function
  protected static function create_ref_function($args, $body) {
    static $n = 0;
    $functionName = sprintf('ref_lambda_%d',++$n);
    $declaration = sprintf('function %s(%s) {%s}',$functionName,$args,$body);
    eval($declaration);

    return $functionName;
  }

  protected static function assert_chain($assert, $chain, $args) {
    foreach($chain as $link) {
      $ret = call_user_func_array($link, $args);
      $ret_a = call_user_func_array($assert, array_merge(array($ret), $args));
      if(!$ret_a) {
        echo("assert failed: $ret\n");
        return false;
      }
    }
    return true;
  }

  protected static function strpos_andMoveReadHead(&$pos, $haystack, $needle) {
    $new_pos = strpos($haystack, $needle, $pos);
    if($new_pos===false) {
      return false;
    } else {
      $pos = $new_pos+strlen($needle);
    }
  }

  public static function checkFormatAndMoveReadHead(&$pos, $string, $assert_chain) {
    $args = array(&$pos, $string);
    $args_str = '$pos, $string';
    $evald_chain = array();
    for($i=0;$i<count($assert_chain);$i++) {
      array_push($evald_chain, self::create_ref_function($args_str, 'return InkScrape::strpos_andMoveReadHead(&$pos, $string, \''.$assert_chain[$i].'\');'));
    }
    if(!self::assert_chain(create_function('$ret, '.$args_str, 'return $ret!==false;'), $evald_chain, $args)) {
      throw new Exception("unrecognized page format");
    }
  }
}

?>