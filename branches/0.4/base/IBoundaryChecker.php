<?php

interface IBoundaryChecker {
  public function boundaries();
  public function setBoundaries($boundaries);

  public function currentPosition();
  public function setPosition($position);

  public function check();
}

?>