<?
// If this file is accessed directly
if ( substr( __FILE__, strlen(__FILE__)-strlen($_SERVER['PHP_SELF']) ) == $_SERVER['PHP_SELF'] )
{
  // Generate an example graph.
  $arr1 = array();
  $arr2 = array();
  $arr3 = array();
  for($i=0; $i<1000; $i++)
  {
    $arr1[] = $i*$i;
    $arr2[] = $i*($i*2);
    $arr3[] = $i*($i*3);
  }

  $LINE1 = new PHPGraph_GraphLine( "Red Line",    $arr1 );
  $LINE1->setColor(0xFF, 0x00, 0x00);

  $LINE2 = new PHPGraph_GraphLine( "Green Line",  $arr2 );
  $LINE2->setColor(0x00, 0xFF, 0x00);

  $LINE3 = new PHPGraph_GraphLine( "Blue Line",   $arr3 );
  $LINE3->setColor(0x00, 0x00, 0xFF);

  $G =  new PHPGraph();
  $G->setTitle('My Test Graph');
  $G->addLine($LINE1, $LINE2, $LINE3);

  $G->createGraph();
}

class PHPGraph
{
  var $linesArr;
  var $title;
  var $width,
      $height,
      $graphWidth,
      $graphHeight;
  var $padding;
  var $highestValue = 0;
  var $minX,
      $minY,
      $maxX,
      $maxY;
  var $pointSpacing = 1;
  var $imageRes;

  var $backgroundColor,
      $borderColor,
      $numberColor,
      $guideLineColor;

  var $maxPointCount = 0;

  var $bgImageRef    = null;

  var $allowAveraging = true;

  function PHPGraph( $width=350, $height=250, $padding=30, $title='' )
  {
    $this->width    = $width;
    $this->height   = $height;
    $this->padding  = $padding;
    $this->title    = $title;

    $this->minX = $padding;
    $this->minY = $padding;
    $this->maxX = $width  - $padding;
    $this->maxY = $height - $padding;

    $this->graphWidth   = $this->maxX - $this->minX;
    $this->graphHeight  = $this->maxY - $this->minY;

    // Create the master resource.
    $this->imageRes = imagecreatetruecolor($this->width, $this->height);

    $this->setColors( imagecolorallocate($this->imageRes, 0xFF, 0xFF,  0xFF),
                      imagecolorallocate($this->imageRes, 0x00, 0x00,  0x00),
                      imagecolorallocate($this->imageRes, 0x00, 0x00,  0x00),
                      imagecolorallocate($this->imageRes, 0XCE, 0XCE,  0XCE)
                    );
  }

  function setBG( $pathToImage )
  {
    $this->bgImageRef = imagecreatetruecolor( $this->width, $this->height );

    $str = strtolower(substr($pathToImage, strlen($pathToImage)-4));

    switch( $str )
    {
      case 'jpeg':
      case '.jpg':
        $tmp = imagecreatefromjpeg($pathToImage);
      break;
      case '.png':
        $tmp = imagecreatefrompng($pathToImage);
      break;
      case '.gif':
        $tmp = imagecreatefromgif($pathToImage);
      break;
    }

    if ( !$tmp ) return;

    list($sourceWidth, $sourceHeight) = getimagesize($pathToImage);
    imagecopyresampled($this->bgImageRef, $tmp, 0, 0, 0, 0, $this->width, $this->height, $sourceWidth, $sourceHeight);

    imagedestroy($tmp);
  }

  function setTitle($str) {
    $this->title = $str;
  }

  function setColors( $backgroundColor, $borderColor, $numberColor, $guideLineColor )
  {
    $this->backgroundColor  = $backgroundColor;
    $this->borderColor      = $borderColor;
    $this->numberColor      = $numberColor;
    $this->guideLineColor   = $guideLineColor;
  }

  function addLine()
  {
    $args = func_get_args();
    if ( !count($args) ) return;

    foreach($args as $arg)
    {
      if ( is_array($arg) )
      {
        foreach($arg as $value)
        {
          if ( !is_a($value, 'PHPGraph_GraphLine') ) {
            die("ERROR: \$line submitted was not of type PHPGraph_GraphLine");
          }
          $this->linesArr[] = $value;
        }
      }
      else
      {
        $this->linesArr[] = $arg;
      }
    }
  }

  function createGraph( $filePath=false )
  {
    $this->_findHighestValue();
    $this->_findPointSpacing();

    if ( $this->bgImageRef )
    {
      imagecopy($this->imageRes, $this->bgImageRef, 0, 0, 0, 0, $this->width, $this->height);
    }
    else
    {
      // Fill in the background of the image
      imagefilledrectangle( $this->imageRes, 0, 0, $this->width, $this->height, $this->backgroundColor );
    }

    $this->_drawGuides();

    // Draw a border
    imageline( $this->imageRes, $this->minX, $this->minY, $this->maxX, $this->minY, $this->borderColor );
    imageline( $this->imageRes, $this->maxX, $this->minY, $this->maxX, $this->maxY, $this->borderColor );
    imageline( $this->imageRes, $this->maxX, $this->maxY, $this->minX, $this->maxY, $this->borderColor );
    imageline( $this->imageRes, $this->minX, $this->maxY, $this->minX, $this->minY, $this->borderColor );

    foreach($this->linesArr as $line)
    {
      $this->_plot( $line );
    }

    $this->_drawTitle();


    $this->_drawLineNames();

    // Send the PNG header information. Replace for JPEG or GIF or whatever
    if ( !$filePath ) {
      header("Content-type: image/png");
      imagepng($this->imageRes);
    }
    else {
      imagepng($this->imageRes, $filePath);
    }

  }

  function _findHighestValue()
  {
    if ( !count($this->linesArr) ) return;

    foreach( $this->linesArr as $line )
    {
      foreach($line->valuesArr as $value)
      {
        $this->highestValue = max($value, $this->highestValue);
      }
    }
  }

  function _findPointSpacing()
  {
    if ( !count($this->linesArr) ) return;

    foreach( $this->linesArr as $line )
    {
      $this->maxPointCount  = max( count($line->valuesArr)-1, $this->maxPointCount );
    }

    $this->pointSpacing = $this->graphWidth / $this->maxPointCount;
  }


  function _plot( $line )
  {
    $color = imagecolorallocate($this->imageRes, $line->red, $line->green,  $line->blue);

    // $oldX = $this->minX;
    // $oldY = $this->maxY;

    $i = 0;
    foreach($line->valuesArr as $label=>$value)
    {
      $y  = $this->graphHeight - floor( ($value / $this->highestValue) * $this->graphHeight );
      $y += $this->padding;

      // $x  = ($i+1) * $this->pointSpacing;
      $x  = $i * $this->pointSpacing;
      $x += $this->padding;

      if ( $i == 0 ) {
        $oldX = $x;
        $oldY = $y;
      }

      imageline($this->imageRes, $oldX, $oldY, $x, $y, $color);

      $oldX = $x;
      $oldY = $y;

      $i++;
    }
  }


  function _drawGuides()
  {
    // Draw guide amt lines
    $guideCount = 10;
    $spacing    = floor($this->graphHeight/$guideCount);
    for($y=0; $y<$guideCount; $y++)
    {
      imageline( $this->imageRes, $this->maxX, ($y*$spacing) + $this->padding, $this->minX, ($y*$spacing) + $this->padding, $this->guideLineColor );
    }

    // Put the amts on the right side
    $x = $this->maxX + 3;
    $chunk = ($this->highestValue / $guideCount);
    if ( $this->highestValue >= 10 ) {
      $chunk = floor($chunk);
    }
    else {
      $chunk = round($chunk, 1);
    }

    for($y=0; $y<$guideCount; $y++)
    {
      $amt = $this->highestValue - ($chunk * $y);
      $str = (string)$amt;

      if ( $str > 1000000000 ) {
        $str = round( $str/1000000000,1 ).'B';
      }
      else if ( $str > 1000000 ) {
        $str = round( $str/1000000, 1 ).'M';
      }
      else if ( $str > 1000 ) {
        $str = round( $str/1000, 1 ).'k';
      }

      for($i=0; $i<strlen($str); $i++)
      {
        imagechar( $this->imageRes, 1, $x+($i*5), $y*$spacing+$this->padding, $str{$i}, $this->numberColor);
      }
    }


    // Put the amts on the left side
    $x = $this->minX - 3 - 5;
    for($y=0; $y<$guideCount; $y++)
    {
      $amt = $this->highestValue - ($chunk * $y);
      $str = (string)$amt;

      if ( $str > 1000000000 ) {
        $str = round( $str/1000000000,1 ).'B';
      }
      else if ( $str > 1000000 ) {
        $str = round( $str/1000000, 1 ).'M';
      }
      else if ( $str > 1000 ) {
        $str = round( $str/1000, 1 ).'k';
      }

      for($i=0; $i<strlen($str); $i++)
      {
        imagechar( $this->imageRes, 1, $x-($i*5), $y*$spacing+$this->padding, $str{strlen($str)-$i-1}, $this->numberColor);
      }
    }
  }

  function _drawLineNames()
  {
    $y = $this->maxY + 3;
    $x = $this->minX;
    foreach( $this->linesArr as $line )
    {
      $color = imagecolorallocate($this->imageRes, $line->red, $line->green,  $line->blue);

      $estimated_width = strlen($line->name) * 5;

      if ( ($x+$estimated_width) > $this->graphWidth ) {
        $x = $this->minX;
        $y += 10;
      }

      for($i=0; $i<strlen($line->name); $i++)
      {
        imagechar( $this->imageRes, 1, $x, $y, $line->name{$i}, $color);
        $x += 5;
      }
      $x += 10;
    }
  }

  function _drawTitle()
  {
    $estimated_width  = strlen($this->title) * 6;
    $x                = ( $this->width / 2 ) - ( $estimated_width / 2 );
    $y                = 3;

    for($i=0; $i<strlen($this->title); $i++)
    {
      imagechar( $this->imageRes, 2, $x + ($i*6), $y, $this->title{$i}, $this->numberColor);
    }

    imageline( $this->imageRes, $x, $y+11, $x+$estimated_width, $y+11, $this->numberColor );
  }
}


class PHPGraph_GraphLine
{
  var $name;
  var $valuesArr;
  var $red    = 0x00;
  var $green  = 0x00;
  var $blue   = 0x00;
  var $totalValues = 0;

  function PHPGraph_GraphLine($name, $valuesArray=false)
  {
    $this->name       = $name;

    if ( $valuesArray ) {
      $this->valuesArr  = $valuesArray;
      $this->totalValues = count( $valuesArray );
    }
    else {
      $this->valuesArr = Array();
    }
    $this->totalValues = count( $this->valuesArr );
  }

  function setColor($red, $green, $blue)
  {
    $this->red   = $red;
    $this->green = $green;
    $this->blue  = $blue;
  }

  function addValue()
  {
    $args = func_get_args();
    if ( !count($args) ) return;

    foreach($args as $arg)
    {
      if ( is_array($arg) )
      {
        foreach($arg as $value)
        {
          $this->valuesArr[] = $value;
          $this->totalValues++;
        }
      }
      else
      {
        $this->valuesArr[] = $arg;
        $this->totalValues++;
      }
    }
  }
}




?>