<?php

include "../../lib/fpdf181/fpdf.php";

class xpdf extends FPDF {

}

class PdfFactory {
  public function build(Document $doc) {
    $pdf = new xpdf();
    $doc->apply($pdf);
  }
}

interface Element {
  function apply(xpdf $pdf);
}

class Document implements Element {
  function apply(xpdf $pdf) {
    echo "applying pdf";
  }
}
# AcceptPageBreak - accept or not automatic page break
# AddFont - add a new font
# AddLink - create an internal link
# AddPage - add a new page
# AliasNbPages - define an alias for number of pages
# Cell - print a cell
# Close - terminate the document
# Error - fatal error
# Footer - page footer
# GetPageHeight - get current page height
# GetPageWidth - get current page width
# GetStringWidth - compute string length
# GetX - get current x position
# GetY - get current y position
# Header - page header
# Image - output an image
# Line - draw a line
# Link - put a link
# Ln - line break
# MultiCell - print text with line breaks
# Output - save or send the document
# PageNo - page number
# Rect - draw a rectangle
# SetAuthor - set the document author
# SetAutoPageBreak - set the automatic page breaking mode
# SetCompression - turn compression on or off
# SetCreator - set document creator
# SetDisplayMode - set display mode
# SetDrawColor - set drawing color
# SetFillColor - set filling color
# SetFont - set font
# SetFontSize - set font size
# SetKeywords - associate keywords with document
# SetLeftMargin - set left margin
# SetLineWidth - set line width
# SetLink - set internal link destination
# SetMargins - set margins
# SetRightMargin - set right margin
# SetSubject - set document subject
# SetTextColor - set text color
# SetTitle - set document title
# SetTopMargin - set top margin
# SetX - set current x position
# SetXY - set current x and y positions
# SetY - set current y position and optionally reset x
# Text - print a string
# Write - print flowing text

?>
