<?php

class Tag {
  protected $m_name;
  protected $m_attributes;
  protected $m_body;

  public function __construct($name="", $attributes=array(), $body="") {
    $this->m_name = $name;
    $this->m_attributes = $attributes;
    $this->m_body = $body;
  }

  public function name() {
    return $this->m_name;
  }

  public function attributes() {
    return $this->m_attributes;
  }

  public function body() {
    return $this->m_body;
  }
}

?>