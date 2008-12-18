<?php

include("base/ForwardBoundaryCheck.php");

class InkScrape {
  public static function checkFrontBoundariesForText($boundaries, $text, $pos=null) {
    $success = true;
    try {
      $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
      $fbc->check();
    } catch(UnmatchedBoundaryException $e) {
      $success = false;
    }
    return $success;
  }

  public static function textFollowingFrontBoundaries($boundaries, $text, $pos=null) {
    $fbc = new ForwardBoundaryCheck($boundaries, $text, $pos);
    $fbc->check();
    $str = substr($text, $fbc->currentPosition());
    return $str;
  }

  public static function boundedText($front, $back, $text, $pos=null) {
    $fbc1 = new ForwardBoundaryCheck($front, $text, $pos);
    $fbc1->check();
    $pos2 = $fbc1->currentPosition();
    $text2 = substr($text, $pos2);

    $fbc2 = new ForwardBoundaryCheck($back, $text2, null);
    $fbc2->check();
    $pos3 = strpos($text2, $back[0]);
    //if i'm still here, that means boundaries are there
    return substr($text, $pos2, $pos3);
  }
}

?>