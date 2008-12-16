<?php

include("base/Asserter.php");
include("base/Predicate.php");
include("base/Condition.php");

class InkScrape {
  private static $pos;
  private static $new_pos;

  public static function predicateCallback($pos) {
    self::$new_pos = $pos;
  }

  public static function findStringAssertPassedCallback() {
    self::$pos = self::$new_pos;
  }

  public static function findStringAssertFailedCallback() {
  }

  public static function checkFormatAndMoveReadHead(&$pos, $string, $assert_chain) {
    $a = new Asserter();
    $a->setAssertPassedCallback(array('InkScrape', 'findStringAssertPassedCallback'));
    $a->setAssertFailedCallback(array('InkScrape', 'findStringAssertFailedCallback'));
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
    InkScrape::predicateCallback($ret);

    return $ret;
  }
}

class FoundStringCondition implements ICondition {
  public function evaluate($value) {
    return $value!==false;;
  }
}

?>