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
    $this->validateParameters();
    $boundaries_count = count($this->m_boundaries);
    for($i=0; $i<$boundaries_count; $i++) {
      $boundary = $this->m_boundaries[$i];
      $boundary_pos = strpos($this->m_data, $boundary, $this->m_pos);
      if($boundary_pos!==false) {
        if($i===0) {
          $this->m_firstBoundaryPosition = $boundary_pos;
        } else {
          $this->m_lastBoundaryPosition = $boundary_pos;
        }
        $this->m_pos = $boundary_pos+strlen($boundary);
      } else {
        throw new UnmatchedBoundaryException($this->m_pos, $boundary, $this->m_data, $i);
      }
    }
  }
}

?>