<?php

class SimpleBoundaryChecker {
  protected $m_boundaries;
  protected $m_pos;

  public function boundaries() {
    return $this->m_boundaries;
  }

  public function setBoundaries($boundaries) {
    $this->boundaries = $boundaries;
  }

  public function currentPosition() {
    return $this->m_pos;
  }

  public function setPosition($position) {
    $this->pos = $position
  }
}

?>