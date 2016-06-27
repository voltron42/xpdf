<?php

include "xpdf.php";
$doc = new Document();
$factory = new PdfFactory();
$factory->build($doc);
echo "finished";
?>