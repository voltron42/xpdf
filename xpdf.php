<?php

include "../../lib/fpdf181/fpdf.php";
include "enums.php";

class xpdf extends FPDF {
  private $isUTF8;
  private $header;
  private $footer;

  function setHeaderAndFooter(Body $header, Body $footer, bool $isUTF8) {
    $this->isUTF8 = $isUTF8;
    $this->header = $header;
    $this->footer = $footer;
  }

  function Footer() {
    $this->footer->apply($this, $this->isUTF8);
  }

  function Header() {
    $this->header->apply($this, $this->isUTF8);
  }
}

interface Element {
  function apply(xpdf $pdf, bool $isUTF8);
}

class Document {
  private $orientation;
  private $unit;
  private $pageSize;
  private $isUTF8;
  private $output;
  private $metadata;
  private $header;
  private $footer;
  private $pages;

  function __construct(
    Orientation $orientation,
    Unit $unit,
    PageSize $pageSize,
    bool $isUTF8,
    Output $output,
    Metadata $metadata,
    Body $header,
    Body $footer,
    Page ...$pages
  ) {
    $this->orientation = $orientation;
    $this->unit = $unit;
    $this->pageSize = $pageSize;
    $this->isUTF8 = $isUTF8;
    $this->output = $output;
    $this->metadata = $metadata;
    $this->header = $header;
    $this->footer = $footer;
    $this->pages = $pages;
  }

  function build() {
    $pdf = new xpdf(
      $this->orientation->toString(),
      $this->unit->toString(),
      $this->pageSize->getSize()
    );
    $pdf->setHeaderAndFooter($this->header, $this->footer, $this->isUTF8);
    $this->metadata->apply($pdf, $isUTF8 || $this->isUTF8);
    foreach($this->pages as $page) {
      $page->apply($pdf, $isUTF8 || $this->isUTF8);
    }
    $this->output->apply($pdf, $isUTF8 || $this->isUTF8);
    echo "applying doc\n";
  }
}

class Output implements Element {
  private $dest;
  private $name;
  private $compress;

  function __construct(
    Destination $dest,
    String $name,
    bool $compress
  ) {
    $this->dest = $dest;
    $this->name = $name;
    $this->$compress;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetCompression($this->compress);
    $pdf->Output(
      $this->dest,
      $this->name,
      $isUTF8
    );
  }
}

class Metadata implements Element {
  private $title;
  private $author;
  private $subject;
  private $keywords;
  private $creator;
  private $aliasNbPages;
  private $fonts;

  function __construct(
    string $title,
    string $author,
    string $subject,
    string $keywords,
    string $creator,
    string $aliasNbPages,
    Font ...$fonts
  ) {
    $this->title = $title;
    $this->author = $author;
    $this->subject = $subject;
    $this->keywords = $keywords;
    $this->creator = $creator;
    $this->aliasNbPages = $aliasNbPages;
    $this->fonts = $fonts;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetTitle($this->title, $isUTF8);
    $pdf->SetAuthor($this->author, $isUTF8);
    $pdf->SetSubject($this->subject, $isUTF8);
    $pdf->SetKeywords($this->keywords, $isUTF8);
    $pdf->SetCreator($this->creator, $isUTF8);
    $pdf->AliasNbPages($this->aliasNbPages);
    foreach($this->fonts as $font) {
      $font->apply($pdf, $isUTF8);
    }
    echo "applying output\n";
  }
}

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


class Page extends Body {
  private $orientation;
  private $pageSize;
  private $rotation;

  function __construct(
    Orientation $orientation,
    PageSize $pageSize,
    PageRotation $rotation,
    BodyElement ...$elements
  ) {
    $this->orientation = $orientation;
    $this->pageSize = $pageSize;
    $this->rotation = $rotation;
    parent::__construct(...$elements);
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->AddPage(
      $this->orientation->toString(),
      $this->pageSize->getSize(),
      $this->rotation->getRotation()
    );
    parent::apply($pdf, $isUTF8);
    echo "applying output\n";
  }
}


class Body implements Element {
  private $elements;

  function __construct(BodyElement ...$elements) {
    $this->elements = $elements;
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    foreach ($this->elements as $element) {
      $element->apply($pdf, $isUTF8);
    }
  }
}

interface BodyElement extends Element {}

# AcceptPageBreak - accept or not automatic page break
# AddLink - create an internal link
# Cell - print a cell
# GetPageHeight - get current page height
# GetPageWidth - get current page width
# GetStringWidth - compute string length
# GetX - get current x position
# GetY - get current y position
# Image - output an image
# Line - draw a line
# Link - put a link
# Ln - line break
# MultiCell - print text with line breaks
# PageNo - page number
# Rect - draw a rectangle
# SetAutoPageBreak - set the automatic page breaking mode
# SetDisplayMode - set display mode
# SetDrawColor - set drawing color
# SetFillColor - set filling color
# SetFont - set font
# SetFontSize - set font size
# SetLeftMargin - set left margin
# SetLineWidth - set line width
# SetLink - set internal link destination
# SetMargins - set margins
# SetRightMargin - set right margin
# SetTextColor - set text color
# SetTopMargin - set top margin
# SetX - set current x position
# SetXY - set current x and y positions
# SetY - set current y position and optionally reset x
# Text - print a string
# Write - print flowing text

?>
