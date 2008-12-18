<?php

require_once("RequestException");

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
    $opt_arr = array_merge($this->m_options, $options);
    $this->validateParameters(&$options);

    $ch = curl_init();
    if(function_exists('curl_setopt_array')) {
      curl_setopt_array($ch, $options);
    } else {
      foreach($options as $opt=>$val) {
        curl_setopt($ch, $opt, $val);
      }
    }

    $returnValue = curl_exec($ch);
    if(empty($returnValue)) {
      throw new RequestException("curl request returned empty value", curl_error($ch));
    }
    curl_close();
  }
}

?>