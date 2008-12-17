<?php

class SimpleBoundaryChecker {
  protected $m_boundaries;
  protected $m_data;
  protected $m_pos;

  public function boundaries() {
    return $this->m_boundaries;
  }

  public function setBoundaries($boundaries) {
    $this->boundaries = $boundaries;
  }

  public function data() {
    return $this->m_data;
  }

  public function setData($data) {
    $this->m_data = $data;
  }

  public function currentPosition() {
    return $this->m_pos;
  }

  public function setPosition($position) {
    $this->pos = $position;
  }

  protected function validateParameters() {
    if(empty($this->m_boundaries))	throw new InvalidArgumentException("expected non-empty array");
    if(empty($this->m_data))	throw new InvalidArgumentException("expected non-empty string");
    if(empty($this->m_pos) && $this->m_pos!==0) $this->m_pos = 0;
  }
}

?>