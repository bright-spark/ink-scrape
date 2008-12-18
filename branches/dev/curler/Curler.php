<?php

require_once("RequestException.php");

class Curler {
  public $options;

  protected $m_previousUrl;
  protected $m_currentUrl;

  protected $response_headers;
  protected $response_body;

  public function __construct($options=array()) {
    $this->options = $options;
    $this->m_previousUrl = '';
    $this->m_currentUrl = '';
  }

  public function sendGetToUrl($url, $options=array()) {
    $options[CURLOPT_HTTPGET] = true;
    $this->sendRequestToUrl($url, $options);
  }

  public function sendPostToUrl($url, $options=array()) {
    $options[CURLOPT_POST] = true;
    $this->sendRequestToUrl($url, $options);
  }

  protected function validateParameters(&$options) {
    $this->m_previousUrl = $this->m_currentUrl;
    $this->m_currentUrl = $options[CURLOPT_URL];
    $this->response_headers = '';
    $this->response_body = '';
    $options[CURLOPT_HEADERFUNCTION] = array($this, '__curlCallbackHeader');
    $options[CURLOPT_WRITEFUNCTION] = array($this, '__curlCallbackBody');
  }

  protected function sendRequestToUrl($url, $options=null) {
    $opt_arr = $this->options;
    foreach($options as $opt=>$val) {
      $opt_arr[$opt] = $val;
    }
    $opt_arr[CURLOPT_URL] = $url;
    $this->validateParameters($opt_arr);

    $ch = curl_init();
    if(function_exists('curl_setopt_array')) {
      curl_setopt_array($ch, $opt_arr);
    } else {
      foreach($opt_arr as $opt=>$val) {
        curl_setopt($ch, $opt, $val);
      }
    }

    $returnValue = curl_exec($ch);
    if(empty($returnValue)) {
      throw new RequestException("curl request failed: ".curl_error($ch), curl_errno($ch));
    }
    curl_close($ch);
  }

  /**
   * Accessors
   */
  protected function previousUrl() {
    return $this->m_previousUrl;
  }

  protected function setPreviousUrl($url) {
    $this->m_previousUrl = $url;
  }

  protected function currentUrl() {
    return $this->m_currentUrl;
  }

  /**
   * Callbacks. Public but not meant to be used.
   */
  public function __curlCallbackHeader($ch, $header) {
    $this->response_headers .= $header;
    return strlen($header);
  }

  public function __curlCallbackBody($ch, $body) {
    $this->response_body .= $body;
    return strlen($body);
  }
}

?>