<?php

class SimpleBoundaryChecker {
  protected $m_boundaries;
  protected $m_firstBoundaryPosition;
  protected $m_lastBoundaryPosition;
  protected $m_lastBoundaryIndex;
  protected $m_data;
  protected $m_pos;

  public function boundaries() {
    return $this->m_boundaries;
  }

  public function setBoundaries($boundaries) {
    $this->boundaries = $boundaries;
  }

  public function firstBoundaryMatchPosition() {
    return $this->m_firstBoundaryPosition;
  }

  public function lastBoundaryMatchPosition() {
    return $this->m_lastBoundaryPosition;
  }

  public function lastBoundaryMatchIndex() {
    return $this->m_lastBoundaryIndex;
  }

  public function totalBoundariesMatched() {
    return $this->m_lastBoundaryIndex + 1;
  }

  public function data() {
    return $this->m_data;
  }

  public function setData($data) {
    $this->m_data = $data;
  }

  public function position() {
    return $this->m_pos;
  }

  public function setPosition($position) {
    $this->pos = $position;
  }

  protected function validateParameters() {
    if(empty($this->m_boundaries))	throw new InvalidArgumentException("expected non-empty array");
    if(empty($this->m_data))	throw new InvalidArgumentException("expected non-empty string");
    if(empty($this->m_pos) && $this->m_pos!==0) $this->m_pos = 0;
    $this->m_firstBoundaryPosition = null;
    $this->m_lastBoundaryPosition = null;
    $this->m_lastBoundaryIndex = null;
  }
}

?>