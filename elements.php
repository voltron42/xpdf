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
    $this->style = array_unique($this->style);
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
      $this->align->getLabel(),
      $this->fill
    );
  }
}

class MultiCell implements BodyElement {
  private $w;
  private $h;
  private $txt;
  private $border;
  private $align;
  private $fill;

  function __construct(
    float $w,
    float $h,
    string $txt,
    CellBorder $border,
    CellAlign $align,
    bool $fill
  ) {
    $this->w = $w;
    $this->h = $h;
    $this->txt = $txt;
    $this->border = $border;
    $this->align = $align;
    $this->fill = $fill;
  }

  static function default(
    float $w = 0,
    float $h = 0,
    string $txt = ""
  ) {
    return new MultiCell(
      $w, $h, $txt,
      CellBorder::NoBorder(),
      CellAlign::Left(),
      false
    );
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->MultiCell(
      $this->w,
      $this->h,
      $this->txt,
      $this->border->getValue(),
      $this->align->getLabel(),
      $this->fill
    );
  }
}

class Ln implements BodyElement {

  private $h;

  function __construct(float $h = 0) {
    $this->h = $h;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    if($this->h > 0) {
      $pdf->Ln($this->h);
    } else {
      $pdf->Ln();
    }
  }
}

class DrawStyle extends Enum {
  private static $raw = array(
    "Draw" => "D",
    "Fill" => "F",
  );

  protected static function rawValues() {
    return self::$raw;
  }

  protected static function className() {
    return __CLASS__."";
  }

  protected static function build($arg) {
    return new DrawStyle($arg);
  }

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class Rect implements BodyElement {
  private $x;
  private $y;
  private $w;
  private $h;
  private $style;

  function __construct(float $x, float $y, float $w, float $h, DrawStyle ...$style) {
    $this->x = $x;
    $this->y = $y;
    $this->w = $w;
    $this->h = $h;
    $this->style = $style;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $stylestring = '';
    $this->style = array_unique($this->style, SORT_REGULAR);
    foreach($this->style as $style) {
      $stylestring .= $style->getLabel();
    }
    $pdf->Rect(
      $this->x,
      $this->y,
      $this->w,
      $this->h,
      $stylestring
    );
  }
}

class Line implements BodyElement {
  private $x1;
  private $y1;
  private $x2;
  private $y2;

  function __construct(
    float $x1,
    float $y1,
    float $x2,
    float $y2
  ) {
    $this->x1 = $x1;
    $this->y1 = $y1;
    $this->x2 = $x2;
    $this->y2 = $y2;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->moveTo($this->x1, $this->y1);
    $pdf->lineTo($this->x2, $this->y2);
    $pdf->closePath();
  }
}

class SetLineWidth implements BodyElement {

  private $width;

  function __construct(float $width) {
    $this->width = $width;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetLineWidth($this->width);
  }
}

class SetFontSize implements BodyElement {

  private $size;

  function __construct(float $size) {
    $this->size = $size;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetFontSize($this->size);
  }
}

class SetTopMargin implements BodyElement {

  private $margin;

  function __construct(float $margin) {
    $this->margin = $margin;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetTopMargin($this->margin);
  }
}

class SetRightMargin implements BodyElement {

  private $margin;

  function __construct(float $margin) {
    $this->margin = $margin;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetRightMargin($this->margin);
  }
}

class SetLeftMargin implements BodyElement {

  private $margin;

  function __construct(float $margin) {
    $this->margin = $margin;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetLeftMargin($this->margin);
  }
}

class SetMargins implements BodyElement {

  private $left;
  private $top;
  private $right;

  function __construct(float $left, float $top, float $right = 0) {
    $this->left = $left;
    $this->top = $top;
    $this->right = $right;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    if ($this->right > 0) {
      $pdf->SetMargins($this->left, $this->top, $this->right);
    } else {
      $pdf->SetMargins($this->left, $this->top);
    }
  }
}

class SetX implements BodyElement {

  private $x;

  function __construct(float $x) {
    $this->x = $x;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetX($this->x);
  }
}

class SetY implements BodyElement {

  private $y;
  private $resetX;

  function __construct(float $y, bool $resetX = true) {
    $this->y = $y;
    $this->resetX = $resetX;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetY($this->y, $this->resetX);
  }
}

class SetXY implements BodyElement {

  private $x;
  private $y;

  function __construct(float $x, float $y) {
    $this->x = $x;
    $this->y = $y;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetXY($this->x, $this->y);
  }
}

class LineCap extends Enum {
  private static $raw = array(
    "Butt" => "butt",
    "RoundCap" => "round",
    "Square" => "square"
  );

  protected static function rawValues() {
    return self::$raw;
  }

  protected static function className() {
    return __CLASS__."";
  }

  protected static function build($arg) {
    return new LineCap($arg);
  }

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class LineJoin extends Enum {
  private static $raw = array(
    "Miter" => "miter",
    "RoundJoin" => "round",
    "Bevel" => "bevel"
  );

  protected static function rawValues() {
    return self::$raw;
  }

  protected static function className() {
    return __CLASS__."";
  }

  protected static function build($arg) {
    return new LineJoin($arg);
  }

  private $label;

  private function __construct($label) {
    $this->label = $label;
  }

  function getLabel() {
    return $this->label;
  }
}

class SetLineCap implements BodyElement {

  private $lineCap;

  function __construct(LineCap $lineCap) {
    $this->lineCap = $lineCap;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->setLineCap($this->lineCap->getLabel());
  }
}

class SetLineJoin implements BodyElement {

  private $lineJoin;

  function __construct(LineJoin $lineJoin) {
    $this->lineJoin = $lineJoin;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->setLineJoin($this->lineJoin->getLabel());
  }
}

class SetDash implements BodyElement {

  private $phase;
  private $dash;

  function __construct(float $phase=0, float ...$dash) {
    $this->phase = $phase;
    $this->dash = $dash;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->setDash($this->phase, ...$this->dash);
  }
}

# SetDash(phase, dash...)
# Path > moveTo(x,y), lineTo(x,y), curveTo(x1,y1,x2,y2,x3,y3), closePath(style)


# Image - output an image
# MultiCell - print text with line breaks

# AddLink - create an internal link
# SetLink - set internal link destination
# Link - put a link

?>
