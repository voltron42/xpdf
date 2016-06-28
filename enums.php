<?php

class Unit {
  private static $values = array("Point" => new Unit("pt"), "Millimeter" => new Unit("mm"), "Centimeter" => new Unit("cm"), "Inch" => new Unit("in"));

  private $label;
  private function __construct($label) {
    $this->label = $label;
  }
  static function parse(string $strval) {
    if (!array_key_exists) {
      throw new Exception("$strval is not a valid value for Unit");
    }
    return $values[$strval];
  }
  function toString() {
    return $this->label;
  }
}

class Destination {
  const Inline = new Destination("I");
  const Download = new Destination("D");
  const LocalFile = new Destination("F");
  const StringOut = new Destination("S");

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function toString() {
    return $this->label;
  }
}

class FontStyle {
  const Bold = new FontStyle("B");
  const Italic = new FontStyle("I");

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function toString() {
    return $this->label;
  }
}

class Orientation {
  const Portrait = new Orientation("P");
  const Landscape = new Orientation("L");

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function toString() {
    return $this->label;
  }
}

class PageRotation {
  const Quarter = new PageRotation(90);
  const Half = new PageRotation(180);
  const ThreeQuarter = new PageRotation(270);
  const Full = new PageRotation(360);

  private $rotation;

  private function __construct(string $rotation) {
    $this->rotation = $rotation;
  }

  function getRotation() {
    return $this->rotation;
  }
}

interface PageSize {
  function getSize();
}

class StdPageSize implements PageSize {
  const A3 = new StdPageSize("A3");
  const A4 = new StdPageSize("A4");
  const A5 = new StdPageSize("A5");
  const Letter = new StdPageSize("Letter");
  const Legal = new StdPageSize("Legal");

  private $label;

  private function __construct(string $label) {
    $this->label = $label;
  }

  function getSize() {
    return $this->label;
  }
}

class CustomPgSize implements PageSize {

  private $width;
  private $height;

  function __construct(float $width, float $height) {
    $this->width = $width;
    $this->height = $height;
  }

  function getSize() {
    return array($this->width, $this->height);
  }
}

 ?>
