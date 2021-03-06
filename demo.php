<?php

include "xpdf.php";
class Demo {
  private static $loremIpsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam egestas vehicula ipsum eu bibendum. Donec id justo dui. Proin vel magna nunc. Donec blandit sapien imperdiet magna efficitur volutpat. Quisque euismod eleifend felis, vel iaculis dolor consequat vitae. Praesent malesuada risus et nisl hendrerit, lacinia scelerisque massa ullamcorper. Morbi erat ipsum, ultrices nec nisi id, luctus varius lacus. Phasellus a tortor consequat, pretium turpis vitae, facilisis magna. Phasellus pellentesque, felis eu scelerisque condimentum, nulla ligula pharetra sapien, ut cursus velit orci in nisi. Ut malesuada et felis volutpat eleifend.\n\nFusce purus nunc, imperdiet nec consequat ut, gravida quis dui. Etiam in molestie enim. Pellentesque euismod ante a purus pharetra convallis. Donec tristique erat eu posuere vulputate. Aenean at auctor dui. Nam quis tellus ornare, gravida ligula eu, mattis mi. Integer venenatis dolor vitae urna aliquam, sit amet porta nibh eleifend. Sed dolor ex, facilisis vitae ligula quis, facilisis rhoncus felis. Quisque dictum viverra orci, eget vehicula diam pulvinar vel. In ac justo sed neque pulvinar aliquam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed commodo tempor eros, non vestibulum sapien.\n\nPhasellus lorem nulla, pulvinar sed velit sit amet, suscipit tincidunt nisi. Cras eleifend magna eget purus pulvinar, sit amet dictum nibh semper. Morbi pretium neque vel justo fringilla, nec placerat purus lobortis. Nunc auctor nulla id sodales bibendum. Quisque non elit ut massa commodo imperdiet. Donec sed commodo dolor. Cras ultricies quam neque, non elementum ex pharetra vel. Donec tincidunt tellus sed nisi convallis, fermentum ultricies velit euismod. Cras pretium sit amet purus quis mollis. Sed id massa nec turpis aliquet dictum. Sed ac arcu id est dapibus condimentum non at orci. Duis metus sem, semper sed mauris ullamcorper, feugiat feugiat orci. Integer tortor tortor, varius in vulputate id, tincidunt vitae sapien.\n\nDonec aliquet varius purus. Phasellus pellentesque at justo ac luctus. Ut a orci suscipit, ullamcorper leo vel, lacinia massa. Fusce facilisis in diam ut mattis. Suspendisse purus eros, dignissim in blandit et, rhoncus eu libero. Pellentesque pretium, est ut euismod auctor, tellus ipsum ornare ipsum, sed sodales massa ex id orci. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.\n\nVivamus pretium velit ut risus aliquam, at gravida erat iaculis. Aliquam nec erat tempus, venenatis justo non, fermentum mi. Proin eu mattis ipsum. Praesent tincidunt fringilla commodo. Sed porta molestie dictum. Aliquam hendrerit dignissim porttitor. Quisque non enim vitae elit pharetra lacinia. Duis a velit lorem. In ac aliquet magna. Donec eu ipsum vitae nisi mollis aliquam. Mauris bibendum nisl sed arcu condimentum pulvinar. Praesent id mauris pharetra est dignissim sollicitudin et vel metus. Nullam euismod, velit at mattis maximus, nibh mi luctus risus, at tincidunt neque dui sit amet diam. Vivamus fringilla odio ac elit commodo, ut bibendum dui vehicula. Mauris metus tortor, efficitur vel ex quis, semper fermentum odio. Cras vulputate sagittis laoreet.";

  public static function main(Destination $dest) {
    $doc = Document::default($dest);
    $doc = new Document(
      new Output($dest),
      Orientation::Landscape(),
      Unit::Inch(),
      new CustomPgSize(8.5,11)
    );
    $page = $doc->newPage();
    $page->addElement(new SetTextColor(new Color(0,0,0)));
    //$page->addElement(new SetFont("Lucida Console",14));
    //$page->addElement(Cell::default(0, 14, "Hello World!"));
    $page->addElement(new Rect(0,0,11,0.75,DrawStyle::Draw()));
    $page->addElement(new Rect(0,0,0.5,8.5,DrawStyle::Draw()));
    $page->addElement(new Rect(0,7.75,11,0.75,DrawStyle::Draw()));
    $page->addElement(new Rect(10.5,0,0.5,8.5,DrawStyle::Draw()));

    $page->addElement(new Rect(18.0/32.0,27.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(82.0/32.0,27.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(146.0/32.0,27.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(210.0/32.0,27.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(274.0/32.0,27.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(18.0/32.0,140.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(82.0/32.0,140.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(146.0/32.0,140.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(210.0/32.0,140.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));
    $page->addElement(new Rect(274.0/32.0,140.0/32.0,60.0/32.0,105.0/32.0,DrawStyle::Draw()));

    $doc->build();
  }
}

?>
