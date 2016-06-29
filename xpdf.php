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

  function StartTransform(){
    //save the current graphic state
    $this->_out('q');
  }

  function ScaleX($s_x, $x='', $y=''){
    $this->Scale($s_x, 100, $x, $y);
  }

  function ScaleY($s_y, $x='', $y=''){
    $this->Scale(100, $s_y, $x, $y);
  }

  function ScaleXY($s, $x='', $y=''){
    $this->Scale($s, $s, $x, $y);
  }

  function Scale($s_x, $s_y, $x='', $y=''){
    if($x === '')
      $x=$this->x;
    if($y === '')
      $y=$this->y;
    if($s_x == 0 || $s_y == 0)
      $this->Error('Please use values unequal to zero for Scaling');
    $y=($this->h-$y)*$this->k;
    $x*=$this->k;
    //calculate elements of transformation matrix
    $s_x/=100;
    $s_y/=100;
    $tm[0]=$s_x;
    $tm[1]=0;
    $tm[2]=0;
    $tm[3]=$s_y;
    $tm[4]=$x*(1-$s_x);
    $tm[5]=$y*(1-$s_y);
    //scale the coordinate system
    $this->Transform($tm);
  }

  function MirrorH($x=''){
    $this->Scale(-100, 100, $x);
  }

  function MirrorV($y=''){
    $this->Scale(100, -100, '', $y);
  }

  function MirrorP($x='',$y=''){
    $this->Scale(-100, -100, $x, $y);
  }

  function MirrorL($angle=0, $x='',$y=''){
    $this->Scale(-100, 100, $x, $y);
    $this->Rotate(-2*($angle-90),$x,$y);
  }

  function TranslateX($t_x){
    $this->Translate($t_x, 0, $x, $y);
  }

  function TranslateY($t_y){
    $this->Translate(0, $t_y, $x, $y);
  }

  function Translate($t_x, $t_y){
    //calculate elements of transformation matrix
    $tm[0]=1;
    $tm[1]=0;
    $tm[2]=0;
    $tm[3]=1;
    $tm[4]=$t_x*$this->k;
    $tm[5]=-$t_y*$this->k;
    //translate the coordinate system
    $this->Transform($tm);
  }

  function Rotate($angle, $x='', $y=''){
    if($x === '')
      $x=$this->x;
    if($y === '')
      $y=$this->y;
    $y=($this->h-$y)*$this->k;
    $x*=$this->k;
    //calculate elements of transformation matrix
    $tm[0]=cos(deg2rad($angle));
    $tm[1]=sin(deg2rad($angle));
    $tm[2]=-$tm[1];
    $tm[3]=$tm[0];
    $tm[4]=$x+$tm[1]*$y-$tm[0]*$x;
    $tm[5]=$y-$tm[0]*$y-$tm[1]*$x;
    //rotate the coordinate system around ($x,$y)
    $this->Transform($tm);
  }

  function SkewX($angle_x, $x='', $y=''){
    $this->Skew($angle_x, 0, $x, $y);
  }

  function SkewY($angle_y, $x='', $y=''){
    $this->Skew(0, $angle_y, $x, $y);
  }

  function Skew($angle_x, $angle_y, $x='', $y=''){
    if($x === '')
      $x=$this->x;
    if($y === '')
      $y=$this->y;
    if($angle_x <= -90 || $angle_x >= 90 || $angle_y <= -90 || $angle_y >= 90)
      $this->Error('Please use values between -90° and 90° for skewing');
    $x*=$this->k;
    $y=($this->h-$y)*$this->k;
    //calculate elements of transformation matrix
    $tm[0]=1;
    $tm[1]=tan(deg2rad($angle_y));
    $tm[2]=tan(deg2rad($angle_x));
    $tm[3]=1;
    $tm[4]=-$tm[2]*$y;
    $tm[5]=-$tm[1]*$x;
    //skew the coordinate system
    $this->Transform($tm);
  }

  function Transform($tm){
    $this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $tm[0],$tm[1],$tm[2],$tm[3],$tm[4],$tm[5]));
  }

  function StopTransform(){
    //restore previous graphic state
    $this->_out('Q');
  }

  function setLineCap(string $cap) {
    $ca = array('butt' => 0, 'round'=> 1, 'square' => 2);
    if (isset($ca[$cap]))
        $this->_out($ca[$cap] . ' J');
  }

  function setLineJoin(string $join) {
    $ja = array('miter' => 0, 'round' => 1, 'bevel' => 2);
    if (isset($ja[$join]))
        $this->_out($ja[$join] . ' j');
  }

  function setDash(float $phase=0, float ...$dash) {
    $dash_string = '';
    foreach ($dash as $i => $v) {
      if ($i > 0)
        $dash_string .= ' ';
      $dash_string .= sprintf('%.2F', $v);
    }
    $this->_out(sprintf('[%s] %.2F d', $dash_string, $phase));
  }

  function moveTo(float $x, float $y) {
    $this->_out(sprintf('%.2F %.2F m', $x * $this->k, ($this->h - $y) * $this->k));
  }

  function lineTo(float $x, float $y) {
    $this->_out(sprintf('%.2F %.2F l', $x * $this->k, ($this->h - $y) * $this->k));
  }

  function curveTo(float $x1, float $y1, float $x2, float $y2, float $x3, float $y3) {
    $this->_out(sprintf(
      '%.2F %.2F %.2F %.2F %.2F %.2F c',
      $x1 * $this->k, ($this->h - $y1) * $this->k,
      $x2 * $this->k, ($this->h - $y2) * $this->k,
      $x3 * $this->k, ($this->h - $y3) * $this->k
    ));
  }

  function closePath(string $style) {
    if($style=='F') {
      $op='f';
    } elseif($style=='FD' || $style=='DF') {
      $op='B';
    } else {
      $op='S';
    }
    $this->_out($op);
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

  static function default(Destination $dest) {
    return new Document(
      new Output($dest),
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
    String $name = "doc.pdf",
    bool $compress = false
  ) {
    $this->dest = $dest;
    $this->name = $name;
    $this->compress = $compress;
  }

  static function default() {
    return new Output(Destination::LocalFile());
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
