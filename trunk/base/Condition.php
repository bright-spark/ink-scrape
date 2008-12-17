<?php

interface ICondition {
  //return $val=<expected> or $val!=<unexpected>
  public function evaluate($value);
}

?>