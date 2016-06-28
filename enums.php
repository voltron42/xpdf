<?php

include "../foreman/enum/enum.php";

class Unit extends Enum {
  private static $raw = array(
    "Point" => "pt",
    "Millimeter" => "mm",
    "Centimeter" => "cm",
    "Inch" => "in"
  );

	protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new Unit($arg);
	}

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class Destination extends Enum {
  private static $raw = array(
    "Inline" => "I",
    "Download" => "D",
    "LocalFile" => "F",
    "StringOut" => "S"
  );

	protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new Destination($arg);
	}

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class FontStyle extends Enum {
  private static $raw = array(
    "Bold" => "B",
    "Italic" => "I"
  );

	protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new FontStyle($arg);
	}

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class Orientation extends Enum {
  private static $raw = array(
    "Portrait" => "P",
    "Landscape" => "L"
  );

	protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new Orientation($arg);
	}

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class PageRotation extends Enum {
  private static $raw = array(
    "Quarter" => 90,
    "Half" => 180,
    "ThreeQuarter" => 270,
    "Full" => 0
  );

	protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new PageRotation($arg);
	}

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

class StdPageSize extends Enum implements PageSize {
  private static $raw = array(
    "A3" => "A3",
    "A4" => "A4",
    "A5" => "A5",
    "Letter" => "Letter",
    "Legal" => "Legal"
  );

  protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new StdPageSize($arg);
	}

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
