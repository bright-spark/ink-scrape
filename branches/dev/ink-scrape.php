<?php

include("base/Asserter.php");
include("base/Predicate.php");
include("base/Condition.php");

class InkScrape {
  private $pos;
  private $rules;
  private $text;

  private $new_pos;

  public function __construct($pos=0, $rules=array(), $text="") {
    $this->pos = $pos;
    $this->rules = $rules;
    $this->text = $text;
  }

  public function currentPosition() {
    return $this->pos;
  }

  public function text() {
    return $this->text;
  }

  public function setNewPos($pos) {
    $this->new_pos = $pos;
  }

  public function findStringAssertPassedCallback() {
    $this->pos = $this->new_pos;
  }

  public function findStringAssertFailedCallback() {
  }

  public function checkTextFormat() {
    $a = new Asserter();
    $a->setAssertPassedCallback(array($this, 'findStringAssertPassedCallback'));
    $a->setAssertFailedCallback(array($this, 'findStringAssertFailedCallback'));
    foreach($this->rules as $p_str) {
      $a->addCase(new FindStringPredicate($this, &$this->pos, $p_str, $this->text), new FoundStringCondition());
    }
    $pass = $a->evaluateAllCases();
    if(!$pass) throw new Exception("unrecognized page format");
  }
}

class FindStringPredicate implements IPredicate {
  public $inst;
  public $pos;
  public $needle;
  public $haystack;

  public function __construct($inst, $pos, $needle, $haystack) {
    $this->inst =& $inst;
    $this->pos =& $pos;
    $this->needle = $needle;
    $this->haystack = $haystack;
  }

  public function invoke() {
    $inst = $this->inst;
    $ret = strpos($this->haystack, $this->needle, $this->pos);
    $inst->setNewPos($ret);

    return $ret;
  }
}

class FoundStringCondition implements ICondition {
  public function evaluate($value) {
    return $value!==false;
  }
}

?>