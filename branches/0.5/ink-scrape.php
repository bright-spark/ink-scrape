<?php

include("base/ForwardBoundaryCheck.php");

class InkScrape {
  public static function checkFrontBoundariesForText($boundaries, $text, $pos=null) {
    $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
  }
}

?>