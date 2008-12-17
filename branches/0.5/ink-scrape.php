<?php

include("base/ForwardBoundaryCheck.php");
include("base/BackwardBoundaryCheck.php");

class InkScrape {
  public static function checkFrontBoundariesForText($boundaries, $text, $pos=null) {
    $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
  }

  public static function checkBackBoundariesForText($boundaries, $text, $pos=null) {
    $fbc = new BackwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
  }
}

?>