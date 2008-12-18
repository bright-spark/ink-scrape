<?php

require_once("RequestException.php");

class Curler {
  public $options;

  protected $m_previousUrl;
  protected $m_currentUrl;

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
    $options[CURLOPT_RETURNTRANSFER] = true;
  }

  protected function sendRequestToUrl($url, $options=null) {
    $opt_arr = array_merge($this->options, $options);
    $opt_arr[CURLOPT_URL] = $url;
    $this->validateParameters(&$opt_arr);

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
}

?>