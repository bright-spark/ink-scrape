<?php

require_once("IBoundaryChecker.php");
require_once("SimpleBoundaryChecker.php");
require_once("UnmatchedBoundaryException.php");

class BackwardBoundaryCheck extends SimpleBoundaryChecker implements IBoundaryChecker {
  public function __construct($boundaries=array(/*string*/), $data='', $pos=null) {
    $this->m_boundaries = $boundaries;
    $this->m_data = $data;
    $this->m_pos = ($pos==null) ? strlen($data) : $pos;
  }

  public function check() {
    $this->m_pos = empty($this->m_pos) ? strlen($this->m_data) : $this->m_pos;
    foreach($this->m_boundaries as $boundary) {
      $haystack = substr($this->m_data, 0, $this->m_pos);
      $pos_new = strrpos($haystack, $boundary);
      if($pos_new!==false) {
        $this->m_pos = $pos_new;
      } else {
        throw new UnmatchedBoundaryException($this->m_pos, $boundary, $this->m_data);
      }
    }
  }
}

?>