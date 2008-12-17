<?php

include("base/ForwardBoundaryCheck.php");

class InkScrape {
  public static function checkFrontBoundariesForText($pos, $boundaries, $text) {
    $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
  }
}

?>