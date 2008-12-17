<?php

include("base/ForwardBoundaryCheck.php");

class InkScrape {
  public static function checkTextFormat($pos, $rules, $text) {
    $fbc = new ForwardBoundaryCheck($rules, $text, $pos);
    $fbc->check();
  }
}

?>