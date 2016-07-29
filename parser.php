<?php

class XPDFParser {
  public function parse(String $raw_xml, Destination $dest) {
    $xml = new XMLReader();
    $xml->xml($raw_xml);
    $node = self::xml2assoc($xml, "root");
    $xml->close();
    $node = $node[0];
    if ($node['name'] != "document") {
      throw new Exception("Invalid document root: " + $node['name']);
    }
    $params = array();
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    if (!isset($children)) {
      $children = array();
    }
    foreach ($attr as $name => $value) {
      $params[$name] = $value;
    }
    foreach ($children as $child) {
      } else if ($child['name'] == 'output') {
        $params['output'] = static::output($child, $dest);
      } else {
        $params[$child['name']] = static::$child['name']($child);
      }
    }
    if (!isset($params['output'])) {
      $params['output'] = new Output($dest);
    }
    return static::document($params, array());
  }

  private static function xml2assoc($xml, $name) {
    $tree = null;
    while($xml->read()) {
      if($xml->nodeType == XMLReader::END_ELEMENT) {
        return $tree;
      } else if($xml->nodeType == XMLReader::ELEMENT) {
        $node = array();
        $node['name'] = self::camelCase($xml->name);
        if($xml->hasAttributes) {
          $attributes = array();
          while($xml->moveToNextAttribute()) {
            $attributes[self::camelCase($xml->name)] = $xml->value;
          }
          $node['attr'] = $attributes;
        }
        if(!$xml->isEmptyElement) {
          $children = self::xml2assoc($xml, $node['name']);
          $node['children'] = $children;
        }
        $tree[] = $node;
      } else if($xml->nodeType == XMLReader::TEXT) {
        $tree[] = $xml->value;
      }
    }
    return $tree;
  }

  private static function camelCase(String $name) {
    $name = ucwords($name,"-");
    $name = lcfirst($name);
    $labels = explode('-',$name);
    return implode('',$labels);
  }

  private static output($node, Destination $dest) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($fileName)) {
      $fileName = "doc.pdf";
    }
    $compress = $attr['compress'];
    if (!isset($compress)) {
      $compress = false;
    }
    return new Output($dest, $fileName, $compress);
  }

  private static $defaults = array(
    "document" => array(
      "orientation" => Orientation::Portrait(),
      "unit" => Unit::Millimeter(),
      "pageSize" => StdPageSize::A4(),
      "header" => null,
      "footer" => null,
      "isUtf8" => false,
      "metadata" => null
    ),
    "page" => array(
      "orientation" => Orientation::Portrait(),
      "pageSize" => StdPageSize::A4(),
      "pageRotation" => PageRotation::Full(),
    ),
    "metadata" => array(
      "title" => '',
      "author" => '',
      "subject" => '',
      "keywords" => '',
      "creator" => '',
      "aliasNbPages" => ''
    ),
    "font" => array(
      "family" => '',
      "file" => '',
      "style" => ''
    ),
    "setFont" => array(
      "family" => '',
      "size" => 8.0,
      "style" => ''
    ),
    "color" => array(
      "r" => 0,
      "g" => 0,
      "b" => 0
    ),
    "text" => array(
      "x" => 0,
      "y" => 0,
      "txt" => ''
    )
  );

  private static $constructors = array(
    "document" => function($attr, $elements) {
      extract($attr);
      return new Document($output, $orientation, $unit, $pageSize, $isUtf8, $metadata, $header, $footer, $pages);
    },
    "page" => function($attr, $elements) {
      extract($attr);
      return new Page($orientation, $pageSize, $pageRotation, $elements);
    },
    "header" => function($attr, $elements) {
      return new Body($elements);
    },
    "footer" => function($attr, $elements) {
      return new Body($elements);
    },
    "metadata" => function($attr, $elements) {
      extract($attr);
      return new Metadata($title, $author, $subject, $keywords, $creator, $aliasNbPages, $elements);
    },
    "font" => function($attr, $elements) {
      extract($attr);
      $styles = array();
      foreach ($explode(",",$style) as $s) {
        $styles[] = FontStyle::valueOf($s);
      }
      return new Font($family, $file, $styles);
    },
    "setFont" => function($attr, $elements) {
      extract($attr);
      $styles = array();
      foreach ($explode(",",$style) as $s) {
        $styles[] = SetFontStyle::valueOf($s);
      }
      return new SetFont($family, $size, $styles);
    },
    "setDrawColor" => function($attr, $elements) {
      return new SetDrawColor($elements[0]);
    },
    "setTextColor" => function($attr, $elements) {
      return new SetTextColor($elements[0]);
    },
    "setFillColor" => function($attr, $elements) {
      return new SetFillColor($elements[0]);
    },
    "text" => function($attr, $elements) {
      extract($attr);
      return new Text($x, $y, $txt);
    }
  );

  public static function __callStatic($name, $args) {
    while(count($args) < 2) {
      $args[] = array();
    }
    $attrs = $args[0];
    $children = $args[1];
    if (!isset($defaults[$name])) {
      $defaults[$name] = array();
    }
    $default = $defaults[$name];
    foreach ($default as $key => $value) {
      if (!isset($attrs[$key])) {
        $attrs[$key] = $value;
      }
    }
    if (!isset($constructors[$name])) {
      $constructors[$name] = function(){return null;};
    }
    $construct = $constructors[$name];
    $elements = static::elements($children);
    return $constructor($attrs, $elements);
  }

  private static elements($children) {
    $elements = array();
    foreach ($children as $child) {
      $elements[] = static::element($child);
    }
    return $elements;
  }

  private static element($node) {
    extract($node);
    return static::$name($attr, $children);
  }

// todo -- convert following functions to above dynamic

  private static write($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($h)) {
      $h = 0;
    }
    if (!isset($txt)) {
      $txt = "";
    }
    return new Write($h, $txt);
  }

  private static cell($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($w)) {
      $w = 0;
    }
    if (!isset($h)) {
      $h = 0;
    }
    if (!isset($txt)) {
      $txt = "";
    }
    if (!isset($border)) {
      $border = CellBorder::NoBorder();
    }
    if (!isset($ln)) {
      $ln = NextPosition::StartNextLine();
    }
    if (!isset($align)) {
      $align = CellAlign::Left();
    }
    if (!isset($fill)) {
      $fill = false;
    }
    return new Cell($w, $h, $txt, $border, $ln, $align, $fill);
  }

  private static multiCell($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($w)) {
      $w = 0;
    }
    if (!isset($h)) {
      $h = 0;
    }
    if (!isset($txt)) {
      $txt = "";
    }
    if (!isset($border)) {
      $border = CellBorder::NoBorder();
    }
    if (!isset($align)) {
      $align = CellAlign::Left();
    }
    if (!isset($fill)) {
      $fill = false;
    }
    return new MultiCell($w, $h, $txt, $border, $align, $fill);
  }

  private static ln($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($h)) {
      $h = 0;
    }
    return new Ln($h);
  }

  private static rect($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($x)) {
      $x = 0;
    }
    if (!isset($y)) {
      $y = 0;
    }
    if (!isset($w)) {
      $w = 0;
    }
    if (!isset($h)) {
      $h = 0;
    }
    if (!isset($style)) {
      $style = "";
    }
    $styles = array();
    foreach ($explode(",",$style) as $s) {
      $styles[] = DrawStyle::valueOf($s);
    }
    return new Rect($x, $y, $w, $h, $styles);
  }

  private static line($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($x1)) {
      $x1 = 0;
    }
    if (!isset($y1)) {
      $y1 = 0;
    }
    if (!isset($x2)) {
      $x2 = 0;
    }
    if (!isset($y2)) {
      $y2 = 0;
    }
    return new Line($x1, $y1, $x2, $y2);
  }

  private static setLineWidth($node) {
    extract($node);
    if (!isset($attr)) {
      $attr = array();
    }
    extract($attr);
    if (!isset($width)) {
      $width = 0;
    }
    return new SetLineWidth($width);
  }
}

?>
