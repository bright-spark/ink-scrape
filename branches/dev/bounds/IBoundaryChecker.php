<?php

interface IBoundaryChecker {
  public function boundaries();
  public function setBoundaries($boundaries);
  public function firstBoundaryMatchPosition();
  public function lastBoundaryMatchPosition();

  public function data();
  public function setData($data);

  public function position();
  public function setPosition($position);

  public function check();
}

?>