<?php

include("bounds/ForwardBoundaryCheck.php");
include("curler/Curler.php");

class InkScrape {
  public $curler;

  public $position;
  public $data;

  public function __construct($options=array()) {
    $this->curler = new Curler($options);
  }

  /**
   * Data-source methods.
   * These provide the instance with data to work on in {@link #data}.
   */
  public function sendGetToUrl($url, $options=array()) {
    $this->curler->sendGetToUrl($url, $options);
    $this->data = $this->curler->responseBody();
    $this->position = 0;
  }

  public function sendPostToUrl($url, $options=array()) {
    $this->curler->sendGetToUrl($url, $options);
    $this->data = $this->curler->responseBody();
    $this->position = 0;
  }

  /**
   * <p>Checks whether <code>$boundaries</code> can be found in front of {@link #data}, starting from {@link #position}.</p>
   * <p>Note: It does not update the <code>position</code> member variable.</p>
   */
  public function checkFrontBoundaries($boundaries) {
    $success = true;
    try {
      $fbc = new ForwardBoundaryCheck($boundaries, $this->data, $this->position);
      $fbc->check();
    } catch(UnmatchedBoundaryException $e) {
      $success = false;
    }
    return $success;
  }

  /**
   * <p>Returns the text in {@link #data} following <code>$boundaries</code>. Boundary checking starts from {@link #position}.</p>
   * <p>Note: It does not update the <code>position</code> member variable.</p>
   */
  public function textFollowingFrontBoundaries($boundaries) {
    $fbc = new ForwardBoundaryCheck($boundaries, $this->data, $this->position);
    $fbc->check();
    $str = substr($text, $fbc->currentPosition());
    return $str;
  }

  /**
   * <p>Returns the text in {@link #data} following <code>$boundaries</code>. Boundary checking starts from {@link #position}.</p>
   * <p>Note: It does not update the <code>position</code> member variable.</p>
   */
  public function boundedText($front, $back) {
    $fbc1 = new ForwardBoundaryCheck($front, $this->data, $this->position);
    $fbc1->check();
    $pos2 = $fbc1->currentPosition();
    $text2 = substr($text, $pos2);

    $fbc2 = new ForwardBoundaryCheck($back, $text2);
    $fbc2->check();
    $pos3 = strpos($text2, $back[0]);
    //if i'm still here, that means boundaries are there
    return substr($this->data, $pos2, $pos3);
  }
}

?>