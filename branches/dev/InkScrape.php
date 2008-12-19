<?php

include("bounds/ForwardBoundaryCheck.php");
include("curler/Curler.php");
include("parsing/Parsing.php");

class InkScrape {
  public $curler;

  public $position;
  public $data;

  protected $m_lastBoundaryChecker;

  public function lastBoundaryChecker() {
    return $this->m_lastBoundaryChecker;
  }

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

  public function sendPostToUrlFields($url, $fields=null, $options=array()) {
    $this->curler->sendPostToUrlFields($url, $fields, $options);
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
      $this->m_lastBoundaryChecker = $fbc;
      $fbc->check();
    } catch(UnmatchedBoundaryException $e) {
      $success = false;
    }
    return $success;
  }

  /**
   * <p>Updates position after checking whether <code>$boundaries</code> can be found in front of {@link #data}, starting from {@link #position}. The position is updated only if all boundaries are found.</p>
   * <p>Note: It does not update the <code>position</code> member variable.</p>
   */
  public function advancePositionWithBoundaries($boundaries) {
    $fbc = new ForwardBoundaryCheck($boundaries, $this->data, $this->position);
    $this->m_lastBoundaryChecker = $fbc;
    try {
      $fbc->check();

      //this won't execute if an exception is thrown.
      $this->position = $fbc->position();
    } catch(UnmatchedBoundaryException $e) {

    }
  }

  /**
   * <p>Updates position after checking whether <code>$boundaries</code> can be found in front of {@link #data}, starting from {@link #position}. The position is updated even if all the boundaries aren't found.</p>
   * <p>Note: It does not update the <code>position</code> member variable.</p>
   */
  public function advancePositionWithBoundariesNonatomic($boundaries) {
    $fbc = new ForwardBoundaryCheck($boundaries, $this->data, $this->position);
    $this->m_lastBoundaryChecker = $fbc;
    try {
      $fbc->check();
    } catch(UnmatchedBoundaryException $e) {
      $this->position = $fbc->position();
    }
  }

  /**
   * <p>Returns the text in {@link #data} following <code>$boundaries</code>. Boundary checking starts from {@link #position}.</p>
   * <p>Note: It does not update the <code>position</code> member variable.</p>
   */
  public function boundedText($front, $back) {
    $fbc1 = new ForwardBoundaryCheck($front, $this->data, $this->position);
    $this->m_lastBoundaryChecker = $fbc1;
    $fbc1->check();
    $fbc1_pos = $fbc1->position();

    $fbc2 = new ForwardBoundaryCheck($back, $this->data, $fbc1_pos);
    $this->m_lastBoundaryChecker = $fbc2;
    $fbc2->check();

    //if i'm still here, that means boundaries are there

    //store position of extent of boundaries
    $this->position = $fbc2->position();
    return substr($this->data, $fbc1_pos, $fbc2->firstBoundaryMatchPosition() - $fbc1_pos);
  }

  /**
   * <p>Treat the text found as form elements and returns a hash representation of it.</p>
   */
  public function boundedTextAsFormInput($front, $back) {
    $text = $this->boundedText($front, $back);

    $tags = Parsing::parseAllNamedUnclosedTags("input", $text);

    $elements_rep = array();
    foreach($tags as $tag) {
      $attrs = $tag->attributes();
      if(array_key_exists("name", $attrs)) {
        $elements_rep[$attrs["name"]] = array_key_exists("value", $attrs) ? $attrs["value"] : "";
      }
    }
    return $elements_rep;
  }

  /**
   * <p>Treat the text found by invoking {@link #boundedText} as a URL, and sends it a GET request.</p>
   */
  public function boundedTextAsGetLink($front, $back, $options=array()) {
    $url = $this->boundedText($front, $back);
    $this->sendGetToUrl($url);
  }

  /**
   * <p>Treat the text found by invoking {@link #boundedText} as a URL, and sends it a POST request.</p>
   */
  public function boundedTextAsPostLink($front, $back, $fields=null, $options=array()) {
    $url = $this->boundedText($front, $back);
    $this->sendPostToUrlFields($url, $fields, $options);
  }

  /**
   * <p>Treats {@link #data} from previous request as a refresh page, and sends a GET request.</p>
   *
   * @throws UnexpectedValueException if a meta refresh tag couldn't be found in the <head>
   */
  public function treatDataAsRefreshPage($options=array()) {
    try {
      $text = $this->boundedText(array('<html>'), array('</html>'));
    } catch (UnmatchedBoundaryException $e) {
      throw new UnexpectedValueException("no <html> element found");
    }
    $matches = array();
    $ret = preg_match_all('/<meta [^>]+>/', $text, &$matches);
    if($ret===false || $ret<=0) throw new UnexpectedValueException("no <meta> tags found");

    $meta_arr = array();
    foreach($matches[0] as $tag) {
      $attr_arr=array();

      $ret = preg_match('/http-equiv="([^"]+)"/', $tag, &$arr);
      if(!($ret===false || $ret<=0)) {
        $attr_arr["http-equiv"] = $arr[1];
      }

      $ret = preg_match('/content="([^"]+)"/', $tag, &$arr);
      if(!($ret===false || $ret<=0)) {
        $attr_arr["content"] = $arr[1];
      }

      array_push($meta_arr, $attr_arr);
    }

    if(count($meta_arr)<=0) throw new UnexpectedValueException("no <meta> tags found");
    $found_refresh = false;
    $url;
    foreach($meta_arr as $meta_tag) {
      if(strtolower(trim($meta_tag["http-equiv"]))!="refresh") {
        continue;
      }
      $ret = preg_match('/url\s*=\s*([^"]+)/', $meta_tag["content"], &$arr);
      if(!($ret===false || $ret<=0)) {
        $url = $arr[1];
        $found_refresh = true;
      }
    }

    if(!$found_refresh)  throw new UnexpectedValueException("no <meta> refresh tags found");

    $this->sendGetToUrl($url, $options);
  }
}

?>