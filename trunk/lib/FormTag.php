<?php

require_once("Tag.php");

class FormTag extends Tag {
  protected $m_inputHash;

  public function __construct($attributes=array(), $body='') {
    parent::__construct('form', $attributes, $body);
    if(!empty($body)) {
      $input_tags = Parsing::parseAllUnclosedTags($body, "input");
      $input_rep = array();
      foreach($input_tags as $tag) {
        $attrs = $tag->attributes();
        if(array_key_exists("name", $attrs)) {
          $input_rep[$attrs["name"]] = array_key_exists("value", $attrs) ? $attrs["value"] : "";
        }
      }
      $this->m_inputHash = $input_rep;
    }
  }

  public function inputs() {
    return $this->m_inputHash;
  }
}

?>