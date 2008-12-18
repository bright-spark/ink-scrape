<?php

require_once("RequestException.php");

class Curler {
  public $options;
  public $provideReferer;
  public $fileTrail;
  public $fileTrailNamer;

  protected $m_previousUrl;
  protected $m_currentUrl;

  protected $response_headers;
  protected $response_body;

  public function __construct($options=array()) {
    $this->options = $options;
    $this->provideReferer = true;
    $this->fileTrail = false;
    $this->m_previousUrl = '';
    $this->m_currentUrl = '';
  }

  public function sendGetToUrl($url, $options=array()) {
    $options[CURLOPT_HTTPGET] = true;
    $this->sendRequestToUrl($url, $options);
  }

  public function sendPostToUrlFields($url, $fields=null, $options=array()) {
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_POSTFIELDS] = $fields;
    $this->sendRequestToUrl($url, $options);
  }

  protected function validateParameters(&$options) {
    $this->m_previousUrl = $this->m_currentUrl;
    $this->m_currentUrl = $options[CURLOPT_URL];
    $this->response_headers = '';
    $this->response_body = '';
    $options[CURLOPT_HEADERFUNCTION] = array($this, '__curlCallbackHeader');
    $options[CURLOPT_WRITEFUNCTION] = array($this, '__curlCallbackBody');
    if($this->provideReferer) {
      $options[CURLOPT_REFERER] = $this->m_previousUrl;
    }
    if(array_key_exists(CURLOPT_POSTFIELDS, $options)) {
      if(is_array($options[CURLOPT_POSTFIELDS])) {
        $fields_arr = array();
        foreach($options[CURLOPT_POSTFIELDS] as $opt=>$val) {
          array_push($fields_arr, "{$opt}={$val}");
        }
        $options[CURLOPT_POSTFIELDS] = implode("&", $fields_arr);
      }
    }
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

    if($this->fileTrail) {
      $this->fileTrailNamer = is_callable($this->fileTrailNamer) ? $this->fileTrailNamer : create_function('$str', 'return md5($str);');
      file_put_contents(call_user_func($this->fileTrailNamer, $url), $this->response_headers."\n".$this->response_body);
    }
  }

  /**
   * Accessors
   */
  public function previousUrl() {
    return $this->m_previousUrl;
  }

  public function setPreviousUrl($url) {
    $this->m_previousUrl = $url;
  }

  public function currentUrl() {
    return $this->m_currentUrl;
  }

  public function setCookieStorePath($path) {
    $this->options[CURLOPT_COOKIEFILE] = $this->options[CURLOPT_COOKIEJAR] = $path;
  }

  public function clearCookieStore() {
    return file_put_contents($this->options[CURLOPT_COOKIEFILE], "");
  }

  public function responseHeaders() {
    return $this->response_headers;
  }

  public function responseBody() {
    return $this->response_body;
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