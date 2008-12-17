<?php

include("base/ForwardBoundaryCheck.php");
include("base/BackwardBoundaryCheck.php");

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

  public static function checkBackBoundariesForText($boundaries, $text, $pos=null) {
    $bbc = new BackwardBoundaryCheck($boundaries, $text, $pos);
    $bbc->check();
    return $bbc;
  }

  public static function textWithBackBoundaries($boundaries, $text, $pos=null) {
    $bbc = self::checkBackBoundariesForText($boundaries, $text, $pos);
    $str = substr($text, 0, $bbc->currentPosition());
    return $str;
  }

  public static function boundedText($front, $back, $text, $pos=null) {
    $fbc = self::checkFrontBoundariesForText($front, $text, $pos);
    $pos2 = $fbc->currentPosition();
    $text2 = substr($text, $pos2);
    $bbc = self::checkBackBoundariesForText($back, $text2);
    return substr($text, $fbc->currentPosition(), $bbc->currentPosition());
  }
}

?>