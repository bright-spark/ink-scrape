<?php

class UnmatchedBoundaryException extends Exception {
  public function __construct($position, $boundary, $text) {
    $str = substr($text, $position, 15);
    parent::__construct("boundary not found in text at or after position {$position}:\n\tboundary: {$boundary}\n\ttext (after {$position}): '{$str}'\n");
  }
}

?>