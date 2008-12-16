<?php

include("base/Asserter.php");
include("base/Predicate.php");
include("base/Condition.php");

class InkScrape {
  // create an pseudo-anonymous function
  protected static function create_ref_function($args, $body) {
    static $n = 0;
    $functionName = sprintf('ref_lambda_%d',++$n);
    $declaration = sprintf('function %s(%s) {%s}',$functionName,$args,$body);
    var_dump($declaration);
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

  protected static function _strpos_andMoveReadHead(&$pos, $haystack, $needle) {
    $new_pos = strpos($haystack, $needle, $pos);
    if($new_pos===false) {
      return false;
    } else {
      $pos = $new_pos+strlen($needle);
    }
  }

  public static function checkFormatAndMoveReadHead(&$pos, $string, $assert_chain) {
    $a = new Asserter();
    foreach($assert_chain as $p_str) {
      $a->addCase(new FindStringPredicate($pos, $p_str, $string), new FoundStringCondition());
    }
    $pass = $a->evaluateAllCases();
    if(!$pass) throw new Exception("unrecognized page format");
  }
}

class FindStringPredicate implements IPredicate {
  public $pos;
  public $needle;
  public $haystack;

  public function __construct($pos, $needle, $haystack) {
    $this->pos = $pos;
    $this->needle = $needle;
    $this->haystack = $haystack;
  }

  public function invoke() {
    $ret = strpos($this->haystack, $this->needle, $this->pos);

    return $ret;
  }
}

class FoundStringCondition implements ICondition {
  public function evaluate($value) {
    return $value!==false;;
  }
}

?>