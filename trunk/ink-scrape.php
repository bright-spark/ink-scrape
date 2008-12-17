<?php

include("base/ForwardBoundaryCheck.php");
include("base/BackwardBoundaryCheck.php");

class InkScrape {
  public static function checkFrontBoundariesForText($boundaries, $text, $pos=null) {
    $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
    return $fbc;
  }

  public static function checkBackBoundariesForText($boundaries, $text, $pos=null) {
    $bbc = new BackwardBoundaryCheck($boundaries, $text, $pos);
    $bbc->check();
    return $bbc;
  }
}

?>