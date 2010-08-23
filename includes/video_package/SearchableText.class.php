<?
/*
SearchableText class by Dylan Squires
Created for experimental site, copied to drunkduck.com.
*/
class SearchableText
{
  var $originalText   = '';       // The original text.
  var $lowerCaseText  = '';       // The original text made lowercase.
  var $embeddedList   = array();  // an array of objects for any found embedded stuff in the text.

  function SearchableText( $txt )
  {
    $this->originalText   = $txt;
  }

  function extractBetween( $start, $end, &$textRef )
  {
    $tmpTxt  = strtolower($textRef);

    $start   = strpos($tmpTxt, strtolower($start));

    if ( $end == '' || $end == false ) {
      $end = strlen( $textRef )-1;
    }
    else {
      $end = strpos($tmpTxt, strtolower($end));
    }

    if ( $end !== false ) $end += strlen($end);

    if ( $start===false || $end===false ) return false;

    $retTxt  = substr( $textRef, $start, $end );

    $piece1  = substr( $textRef, 0, $start);
    $piece2  = substr( $textRef, $end);
    $textRef = $piece1 . $piece2;

    return $retTxt;
  }





  function findTags()
  {
    while( true )
    {
      if ( $txtSegment = $this->extractBetween('<object', '/object>', $this->originalText) )
      {
        if ( $e = $this->parseOutObject($txtSegment) ) {
          $this->embeddedList[] = $e;
        }
      }
      else if ( $txtSegment = $this->extractBetween('<embed', '/embed>', $this->originalText) )
      {
        if ( $e = $this->parseOutEmbed($txtSegment) ) {
          $this->embeddedList[] = $e;
        }
      }
      else if ( $txtSegment = $this->extractBetween('<embed', '>', $this->originalText) )
      {
        if ( $e = $this->parseOutEmbed($txtSegment) ) {
          $this->embeddedList[] = $e;
        }
      }
      else if ( $txtSegment = $this->extractBetween('http://', '', $this->originalText) )
      {
        if ( $e = $this->parseOutEmbedURL($txtSegment) ) {
          $this->embeddedList[] = $e;
        }
      }
      else
      {
        break;
      }
    }

    if ( count($this->embeddedList) ) return true;
    return false;
  }










  function parseOutObject( $txt )
  {
    $e = new Embed();

    // Extract URL
    if ( preg_match('`value *= *["\']*(http:\/\/[a-zA-Z_0-9\/\.\?=\-&]+)["\']*`mi', $txt, $match) ) {
      $e->url = $match[1];
    }
    else {
      return false;
    }

    // Extract Width
    if ( preg_match('`width *= *["|\']*([0-9]+)["|\']*`mi', $txt, $match) ) {
      $e->width = $match[1];
    }
    else if ( preg_match('`width *: *([0-9]+)`mi', $txt, $match) ) {
      $e->width = $match[1];
    }
    else {
      return false;
    }

    // Extract Height
    if ( preg_match('`height *= *["|\']*([0-9]+)["|\']*`mi', $txt, $match) ) {
      $e->height = $match[1];
    }
    else if ( preg_match('`height *: *([0-9]+)`mi', $txt, $match) ) {
      $e->height = $match[1];
    }
    else {
      return false;
    }

    // Extract "Type"
    if ( preg_match('`type *= *["|\']*([a-zA-Z_0-9\/\-]*)["|\']*`mi', $txt, $match) ) {
      $e->type = $match[1];
    }

    return $e;
  }





  function parseOutEmbed( $txt )
  {
    $e = new Embed();

    // Extract URL
    if ( preg_match('`src *= *["\']*(http:\/\/[a-zA-Z_0-9\/\.\?=\-&]+)["\']*`mi', $txt, $match) ) {
      $e->url = $match[1];
    }
    else {
      return false;
    }

    // Extract Width
    if ( preg_match('`width *= *["|\']*([0-9]+)["|\']*`mi', $txt, $match) ) {
      $e->width = $match[1];
    }
    else if ( preg_match('`width *: *([0-9]+)`mi', $txt, $match) ) {
      $e->width = $match[1];
    }
    else {
      return false;
    }


    // Extract Height
    if ( preg_match('`height *= *["|\']*([0-9]+)["|\']*`mi', $txt, $match) ) {
      $e->height = $match[1];
    }
    else if ( preg_match('`height *: *([0-9]+)`mi', $txt, $match) ) {
      $e->height = $match[1];
    }
    else {
      return false;
    }

    // Extract "Type"
    if ( preg_match('`type *= *["|\']*([a-zA-Z_0-9\/\-]*)["|\']*`mi', $txt, $match) ) {
      $e->type = $match[1];
    }

    // Extract any 'flashvars'
    if ( preg_match('`flashvars *= *["|\'](.*)["|\']`miU', $txt, $match) ) {
      if ( strstr($e->url, '?') ) {
        $e->url .= '&'.$match[1];
      }
      else {
        $e->url .= '?'.$match[1];
      }
    }
    return $e;
  }

  function parseOutEmbedURL( $txt )
  {
    $e = new Embed();

    // Extract URL
    if ( preg_match('`.*(http:\/\/[a-zA-Z_0-9\/\.\?=\-&]+)`mi', $txt, $match) ) {
      $e->url = $match[1];
    }
    else {
      return false;
    }

    $e->width   = 400;
    $e->height  = 300;

    // Type is assumed to be flash
    $e->type = 'application/x-shockwave-flash';

    return $e;
  }

}

class Embed
{
  var $url;
  var $width;
  var $height;
  var $type;
}
?>