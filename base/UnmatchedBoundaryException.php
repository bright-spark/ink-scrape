<?php

class UnmatchedBoundaryException extends Exception {
  public function __construct($position, $boundary, $text) {
    parent::__construct("boundary not found in text at or after position {$position}:\n\tboundary: {$boundary}\n\ttext: {$text}\n");
  }
}

?>