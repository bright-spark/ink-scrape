<?php

class UnmatchedBoundaryException extends Exception {
  private $m_position;
  private $m_boundary;
  private $m_text;
  private $m_matchCount;

  public function position() {
    return $this->m_position;
  }

  public function boundary() {
    return $this->m_boundary;
  }

  public function text() {
    return $this->m_text;
  }

  public function matchCount() {
    return $this->m_matchCount;
  }

  public function __construct($position, $boundary, $text, $matchCount=0) {
    $this->m_position = $position;
    $this->m_boundary = $boundary;
    $this->m_text = $text;
    $this->m_matchCount = $matchCount;

    $str = substr($text, $position, 15);
    parent::__construct("boundary not found in text at or after position {$position}:\n\tboundary: {$boundary}\n\ttext (after {$position}): '{$str}'\n");
  }
}

?>