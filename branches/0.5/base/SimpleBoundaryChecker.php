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
}

?>