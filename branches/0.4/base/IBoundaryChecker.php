<?php

interface IBoundaryChecker {
  public function boundaries();
  public function setBoundaries($boundaries);

  public function data();
  public function setData($data);

  public function currentPosition();
  public function setPosition($position);

  public function check();
}

?>