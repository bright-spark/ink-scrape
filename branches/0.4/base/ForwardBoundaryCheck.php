<?php

require_once("IBoundaryChecker.php");
require_once("SimpleBoundaryChecker.php");

class ForwardBoundaryCheck extends SimpleBoundaryChecker implements IBoundaryChecker {
  public function check() {
    foreach($this->m_boundaries as $boundary) {
      $pos_new = strpos($this->m_data, $boundary, $this->m_pos);
      if($pos_new!==false) {
        $this->m_pos = $pos_new;
      } else {
        throw new Exception("unrecognized page format");
      }
    }
  }
}

?>