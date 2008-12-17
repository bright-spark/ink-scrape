<?php

class UnmatchedBoundaryException extends Exception {
  public function __construct($boundary, $text) {
    parent::__construct("boundary not found in text:\n\tboundary: {$boundary}\n\ttext: {$text}\n");
  }
}

?>