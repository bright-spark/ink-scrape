<?php

class Asserter {
  public $cases;

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
        return false;
      }
    }
    return true;
  }
}

?>