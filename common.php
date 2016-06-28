<?php

include "enums.php";

interface Element {
  function apply(xpdf $pdf, bool $isUTF8);
}

interface BodyElement extends Element {}

class Font implements Element {
  private $family;
  private $style;
  private $file;

  function __construct(
    string $family,
    String $file,
    FontStyle ...$style
  ) {
    $this->family = $family;
    $this->file = $file;
    $this->style = $style;
  }

  function addStyle(FontStyle $style) {
    array_push($this->style, $style);
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $stylestring = '';
    foreach($this->style as $style) {
      $stylestring .= $style;
    }
    $pdf->AddFont(
      $this->family,
      $stylestring,
      $this->file
    );
  }
}

?>
