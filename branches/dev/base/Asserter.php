<?php

class Asserter {
  public $cases;

  protected $assertPassedCallback;
  protected $assertFailedCallback;

  function __construct() {
    $this->cases = array();
  }

  public function addCase($apredicate, $acondition) {
    array_push($this->cases, array("p"=>$apredicate, "c"=>$acondition));
  }

  public function evaluateAllCases() {
    foreach($this->cases as $case) {
      $p = $case["p"];
      $c = $case["c"];

      $val = $p->invoke();
      if(!$c->evaluate($val)) {
        call_user_func($this->assertFailedCallback);
        return false;
      } else {
        call_user_func($this->assertPassedCallback);
      }
    }
    return true;
  }

  public function setAssertPassedCallback($c) {
    if(is_callable($c)) {
      $this->assertPassedCallback = $c;
    } else {
      throw new InvalidArgumentException("expected callback");
    }
  }

  public function setAssertFailedCallback($c) {
    if(is_callable($c)) {
      $this->assertFailedCallback = $c;
    } else {
      throw new InvalidArgumentException("expected callback");
    }
  }
}

?>