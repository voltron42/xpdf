<?php

include "common.php";

class SetFont implements BodyElement {
  private $family;
  private $size;
  private $style;

  function __construct(
    string $family = "",
    float $size = 0,
    SetFloatStyle ...$style
  ) {
    $this->family = $family;
    $this->size = $size;
    $this->style = $style;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $stylestring = '';
    foreach($this->style as $style) {
      $stylestring .= $style;
    }
    if($this->size > 0) {
      $pdf->SetFont(
        $this->family,
        $stylestring,
        $this->size
      );
    } else {
      if(strlen($stylestring) > 0) {
        $pdf->SetFont($this->family, $stylestring);
      } else {
        $pdf->SetFont($this->family);
      }
    }
  }
}

class SetFontStyle extends Enum {
  private static $raw = array(
    "Bold" => "B",
    "Italic" => "I",
    "Underline" => "U"
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

class Color {
  private $r;
  private $g;
  private $b;

  function __construct(int $r, int $g, int $b) {
    $this->r = $r;
    $this->g = $g;
    $this->b = $b;
  }

  function getRGB() {
    return array($this->r, $this->g, $this->b);
  }
}

class SetDrawColor implements BodyElement {
  private $color;

  function __construct(Color $color) {
    $this->color = $color;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetDrawColor(...$this->color->getRGB());
  }
}

class SetFillColor implements BodyElement {
  private $color;

  function __construct(Color $color) {
    $this->color = $color;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetFillColor(...$this->color->getRGB());
  }
}

class SetTextColor implements BodyElement {
  private $color;

  function __construct(Color $color) {
    $this->color = $color;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetTextColor(...$this->color->getRGB());
  }
}

class Text implements BodyElement {
  private $x;
  private $y;
  private $txt;

  function __construct(float $x, float $y, string $txt) {
    $this->x = $x;
    $this->y = $y;
    $this->txt = $txt;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->Text($this->x, $this->y, $this->txt);
  }
}

class Write implements BodyElement {
  private $h;
  private $txt;

  function __construct(float $h, string $txt) {
    $this->h = $h;
    $this->txt = $txt;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->Write($this->h, $this->txt);
  }
}


class CellBorder extends Enum {
  private static $raw = array(
    "NoBorder" => 0
  );

  protected static function rawValues() {
    return self::$raw;
  }

  protected static function className() {
    return __CLASS__."";
  }

  protected static function build($arg) {
    return new CellBorder($arg);
  }

  private $value;

  private function __construct($value) {
    $this->value = $value;
  }

  function getValue() {
    return $this->value;
  }
}

class NextPosition extends Enum {
  private static $raw = array(
    "ToTheRight" => 0,
    "StartNextLine" => 1,
    "Below" => 2
  );

	protected static function rawValues() {
		return self::$raw;
	}

	protected static function className() {
		return __CLASS__."";
	}

	protected static function build($arg) {
		return new NextPosition($arg);
	}

  private $value;

  private function __construct($value) {
    $this->value = $value;
  }

  function getValue() {
    return $this->value;
  }
}

class CellAlign extends Enum {
  private static $raw = array(
    "Left" => "L",
    "Center" => "C",
    "Right" => "R"
  );

  protected static function rawValues() {
    return self::$raw;
  }

  protected static function className() {
    return __CLASS__."";
  }

  protected static function build($arg) {
    return new CellAlign($arg);
  }

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class Cell implements BodyElement {
  private $w;
  private $h;
  private $txt;
  private $border;
  private $ln;
  private $align;
  private $fill;

  function __construct(
    float $w,
    float $h,
    string $txt,
    CellBorder $border,
    NextPosition $ln,
    CellAlign $align,
    bool $fill
  ) {
    $this->w = $w;
    $this->h = $h;
    $this->txt = $txt;
    $this->border = $border;
    $this->ln = $ln;
    $this->align = $align;
    $this->fill = $fill;
  }

  static function default(
    float $w = 0,
    float $h = 0,
    string $txt = ""
  ) {
    return new Cell(
      $w, $h, $txt,
      CellBorder::NoBorder(),
      NextPosition::StartNextLine(),
      CellAlign::Left(),
      false
    );
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->Cell(
      $this->w,
      $this->h,
      $this->txt,
      $this->border->getValue(),
      $this->ln->getValue(),
      $this->align->getLabel()
    );
  }
}

# SetFontSize - set font size
# SetLeftMargin - set left margin
# SetLineWidth - set line width
# SetMargins - set margins
# SetRightMargin - set right margin
# SetTopMargin - set top margin
# SetX - set current x position
# SetXY - set current x and y positions
# SetY - set current y position and optionally reset x
# Image - output an image
# Line - draw a line
# Link - put a link
# Ln - line break
# MultiCell - print text with line breaks
# Rect - draw a rectangle
# AddLink - create an internal link
# SetLink - set internal link destination

?>
