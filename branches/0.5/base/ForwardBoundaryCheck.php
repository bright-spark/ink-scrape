<?php

require_once("IBoundaryChecker.php");
require_once("SimpleBoundaryChecker.php");
require_once("UnmatchedBoundaryException.php");

class ForwardBoundaryCheck extends SimpleBoundaryChecker implements IBoundaryChecker {
  public function __construct($boundaries, $data, $pos) {
    $this->m_boundaries = $boundaries;
    $this->m_data = $data;
    $this->m_pos = $pos;
  }

  public function check() {
    $count = 0;
    $this->validateParameters();
    foreach($this->m_boundaries as $boundary) {
      $pos_new = strpos($this->m_data, $boundary, $this->m_pos);
      if($pos_new!==false) {
        $this->m_pos = $pos_new+strlen($boundary);
        $count++;
      } else {
        throw new UnmatchedBoundaryException($this->m_pos, $boundary, $this->m_data, $count);
      }
    }
  }
}

?>