<?php

include("base/ForwardBoundaryCheck.php");

class InkScrape {
  public static function checkFrontBoundariesForText($boundaries, $text, $pos=null) {
    $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
    return $fbc;
  }

  public static function textWithFrontBoundaries($boundaries, $text, $pos=null) {
    $fbc = self::checkFrontBoundariesForText($boundaries, $text, $pos);
    $str = substr($text, $fbc->currentPosition());
    return $str;
  }

  public static function boundedText($front, $back, $text, $pos=null) {
    $fbc1 = self::checkFrontBoundariesForText($front, $text, $pos);
    $pos2 = $fbc1->currentPosition();
    $text2 = substr($text, $pos2);
    $fbc2 = self::checkFrontBoundariesForText($back, $text2);
    $pos3 = strpos($text2, $back[0]);
    //if i'm still here, that means boundaries are there
    return substr($text, $pos2, $pos3);
  }
}

?>