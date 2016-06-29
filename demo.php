<?php

include "xpdf.php";
class Demo {
  private static $loremIpsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam egestas vehicula ipsum eu bibendum. Donec id justo dui. Proin vel magna nunc. Donec blandit sapien imperdiet magna efficitur volutpat. Quisque euismod eleifend felis, vel iaculis dolor consequat vitae. Praesent malesuada risus et nisl hendrerit, lacinia scelerisque massa ullamcorper. Morbi erat ipsum, ultrices nec nisi id, luctus varius lacus. Phasellus a tortor consequat, pretium turpis vitae, facilisis magna. Phasellus pellentesque, felis eu scelerisque condimentum, nulla ligula pharetra sapien, ut cursus velit orci in nisi. Ut malesuada et felis volutpat eleifend.\n\nFusce purus nunc, imperdiet nec consequat ut, gravida quis dui. Etiam in molestie enim. Pellentesque euismod ante a purus pharetra convallis. Donec tristique erat eu posuere vulputate. Aenean at auctor dui. Nam quis tellus ornare, gravida ligula eu, mattis mi. Integer venenatis dolor vitae urna aliquam, sit amet porta nibh eleifend. Sed dolor ex, facilisis vitae ligula quis, facilisis rhoncus felis. Quisque dictum viverra orci, eget vehicula diam pulvinar vel. In ac justo sed neque pulvinar aliquam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed commodo tempor eros, non vestibulum sapien.\n\nPhasellus lorem nulla, pulvinar sed velit sit amet, suscipit tincidunt nisi. Cras eleifend magna eget purus pulvinar, sit amet dictum nibh semper. Morbi pretium neque vel justo fringilla, nec placerat purus lobortis. Nunc auctor nulla id sodales bibendum. Quisque non elit ut massa commodo imperdiet. Donec sed commodo dolor. Cras ultricies quam neque, non elementum ex pharetra vel. Donec tincidunt tellus sed nisi convallis, fermentum ultricies velit euismod. Cras pretium sit amet purus quis mollis. Sed id massa nec turpis aliquet dictum. Sed ac arcu id est dapibus condimentum non at orci. Duis metus sem, semper sed mauris ullamcorper, feugiat feugiat orci. Integer tortor tortor, varius in vulputate id, tincidunt vitae sapien.\n\nDonec aliquet varius purus. Phasellus pellentesque at justo ac luctus. Ut a orci suscipit, ullamcorper leo vel, lacinia massa. Fusce facilisis in diam ut mattis. Suspendisse purus eros, dignissim in blandit et, rhoncus eu libero. Pellentesque pretium, est ut euismod auctor, tellus ipsum ornare ipsum, sed sodales massa ex id orci. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.\n\nVivamus pretium velit ut risus aliquam, at gravida erat iaculis. Aliquam nec erat tempus, venenatis justo non, fermentum mi. Proin eu mattis ipsum. Praesent tincidunt fringilla commodo. Sed porta molestie dictum. Aliquam hendrerit dignissim porttitor. Quisque non enim vitae elit pharetra lacinia. Duis a velit lorem. In ac aliquet magna. Donec eu ipsum vitae nisi mollis aliquam. Mauris bibendum nisl sed arcu condimentum pulvinar. Praesent id mauris pharetra est dignissim sollicitudin et vel metus. Nullam euismod, velit at mattis maximus, nibh mi luctus risus, at tincidunt neque dui sit amet diam. Vivamus fringilla odio ac elit commodo, ut bibendum dui vehicula. Mauris metus tortor, efficitur vel ex quis, semper fermentum odio. Cras vulputate sagittis laoreet.";

  public static function main(Destination $dest) {
    $doc = Document::default($dest);
    $page = $doc->newPage();
    $page->addElement(new SetFont("Arial",14));
    $page->addElement(new SetTextColor(new Color(0,0,128)));
    $page->addElement(Cell::default(0, 14, "Hello World!"));
    $page->addElement(Cell::default(0, 14, "Hello Again!"));
    $page->addElement(new SetDrawColor(new Color(0,255,0)));
    $page->addElement(new SetFillColor(new Color(255,0,0)));
    $page->addElement(new SetLineWidth(1));
    $page->addElement(new SetLineJoin(LineJoin::Miter()));
    $page->addElement(new Rect(115,50,15,25));
    $page->addElement(new Rect(135,50,15,25,DrawStyle::Fill()));
    $page->addElement(new SetLineJoin(LineJoin::RoundJoin()));
    $page->addElement(new Rect(115,80,15,25,DrawStyle::Draw()));
    $page->addElement(new SetLineJoin(LineJoin::Bevel()));
    $page->addElement(new Rect(135,80,15,25,DrawStyle::Draw(),DrawStyle::Fill()));
    $page->addElement(new SetDrawColor(new Color(0,0,255)));
    $page->addElement(new Line(115,50,150,105));
    $page->addElement(new SetDrawColor(new Color(128,0,255)));
    $page->addElement(new SetDash(0,10));
    $page->addElement(new Line(115,105,150,50));
    $page->addElement(new SetDrawColor(new Color(128,0,0)));
    $page->addElement(new SetDash(0,5,2));
    $page->addElement(new Line(115,120,150,120));
    $page->addElement(new SetDash(0,10,5));
    $page->addElement(new Line(115,130,150,130));
    $page->addElement(new SetDash(0,15,20));
    $page->addElement(new Line(115,140,150,140));
    $page->addElement(new SetDash());
    $page->addElement(new SetLineCap(LineCap::Butt()));
    $page->addElement(new Line(115,150,150,150));
    $page->addElement(new SetLineCap(LineCap::RoundCap()));
    $page->addElement(new Line(115,160,150,160));
    $page->addElement(new SetLineCap(LineCap::Square()));
    $page->addElement(new Line(115,170,150,170));
    $page->addElement(new SetFontSize(10));
    $page->addElement(MultiCell::default(100,10,self::$loremIpsum));
    //$page->addElement(new Text(15,15,"Hello World!"));

    $doc->build();
  }
}

?>
