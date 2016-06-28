<?php

include "../../lib/fpdf181/fpdf.php";
include "elements.php";

class xpdf extends FPDF {
  private $isUTF8;
  private $header;
  private $footer;

  function setUTF8(bool $isUTF8) {
    $this->isUTF8 = $isUTF8;
  }

  function setHeader(Body $header) {
    $this->header = $header;
  }

  function setFooter(Body $footer) {
    $this->footer = $footer;
  }

  function Footer() {
    if (isset($this->footer)) {
      $this->footer->apply($this, $this->isUTF8);
    }
  }

  function Header() {
    if (isset($this->header)) {
      $this->header->apply($this, $this->isUTF8);
    }
  }
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
    Output $output,
    Orientation $orientation,
    Unit $unit,
    PageSize $pageSize,
    Body $header = NULL,
    Body $footer = null,
    bool $isUTF8 = false,
    Metadata $metadata = NULL,
    Page ...$pages
  ) {
    $this->output = $output;
    $this->orientation = $orientation;
    $this->unit = $unit;
    $this->pageSize = $pageSize;
    $this->isUTF8 = $isUTF8;
    $this->metadata = $metadata;
    $this->header = $header;
    $this->footer = $footer;
    $this->pages = $pages;
  }

  static function default() {
    return new Document(
      Output::default(),
      Orientation::Portrait(),
      Unit::Millimeter(),
      StdPageSize::A4()
    );
  }

  function newPage() {
    $page = new Page(
      $this->orientation,
      $this->pageSize,
      PageRotation::Full()
    );
    $this->addPage($page);
    return $page;
  }

  function addPage(Page $page) {
    array_push($this->pages, $page);
  }

  function build() {
    $pdf = new xpdf(
      $this->orientation->getLabel(),
      $this->unit->getLabel(),
      $this->pageSize->getSize()
    );
    $pdf->setUTF8($isUTF8 || $this->isUTF8);
    if (isset($this->header)) {
      $pdf->setHeader($this->header);
    }
    if (isset($this->footer)) {
      $pdf->setHeader($this->footer);
    }
    if (isset($this->metadata)) {
      $this->metadata->apply($pdf, $isUTF8 || $this->isUTF8);
    }
    foreach($this->pages as $page) {
      $page->apply($pdf, $isUTF8 || $this->isUTF8);
    }
    $this->output->apply($pdf, $isUTF8 || $this->isUTF8);
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
    $this->compress = $compress;
  }

  static function default() {
    return new Output(
      Destination::LocalFile(),
      "doc.pdf",
      false
    );
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    $pdf->SetCompression($this->compress);
    $pdf->Output(
      $this->dest->getLabel(),
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

  function addFont(Font $font) {
    array_push($this->fonts, $font);
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
  }
}

class Body implements Element {
  private $elements;

  function __construct(BodyElement ...$elements) {
    $this->elements = $elements;
  }

  function addElement(BodyElement $elem) {
    array_push($this->elements, $elem);
  }

  function apply(xpdf $pdf, bool $isUTF8) {
    foreach ($this->elements as $element) {
      $element->apply($pdf, $isUTF8);
    }
  }
}

class Page extends Body implements Element {
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
      $this->orientation->getLabel(),
      $this->pageSize->getSize(),
      $this->rotation->getRotation()
    );
    parent::apply($pdf, $isUTF8);
  }
}

# SetAutoPageBreak - set the automatic page breaking mode
# SetDisplayMode - set display mode

# AcceptPageBreak - accept or not automatic page break
# GetPageHeight - get current page height
# GetPageWidth - get current page width
# GetStringWidth - compute string length
# GetX - get current x position
# GetY - get current y position
# PageNo - page number


?>
