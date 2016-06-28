<?php

include "xpdf.php";
$doc = Document::default();
$page = $doc->newPage();
$page->addElement(new SetFont("Arial",14));
$page->addElement(new SetTextColor(new Color(0,0,128)));
$page->addElement(Cell::default(0, 14, "Hello World!"));
$page->addElement(Cell::default(0, 14, "Hello Again!"));
//$page->addElement(new Text(15,15,"Hello World!"));

var_dump($doc);

$doc->build();

?>
