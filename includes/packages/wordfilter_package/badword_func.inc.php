<?
require_once('badword_data.inc.php');

function getAllBadWords( $string )
{
  $_WORDS = array();
  while( $word = doBadWordCheck($string) )
  {
    $_WORDS[] = $word;
    $string = str_replace($word->badWord, "", $string);
  }

  return $_WORDS;
}

// pass in a string of words to do validation.
function doBadWordCheck( $string )
{
  global $_WORDS;

  $OGString = $string;
  $string   = prepareString( $string );

  foreach($_WORDS as $cat=>$categoryArr)
  {
    if ( count($categoryArr[BW_STRING]) )
    {
      foreach($categoryArr[BW_STRING] as $word)
      {
        if ( $MATCH = strstr($string, $word) )
        {
          $MATCH = trim($MATCH);
          $MATCH = substr($MATCH, 0, strlen($word));

          $builtRegex = array();
          for ($i=0; $i<strlen($MATCH); $i++) {
            $builtRegex[] = charToRegex($MATCH{$i});
          }
          $builtRegex = implode(BW_NON_WORD_PATTERN.'*', $builtRegex);

          //preg_match('/'.$builtRegex.'/i', $OGString, $NEW_MATCHES, PREG_OFFSET_CAPTURE);
          //$REAL_MATCH = $NEW_MATCHES[0][0];
          preg_match('/'.$builtRegex.'/i', $OGString, $NEW_MATCHES);
          $REAL_MATCH = $NEW_MATCHES[0];

          $badword = new BadWord($REAL_MATCH, $cat);
          return $badword;
        }
      }
    }

    if ( count($categoryArr[BW_REGEX]) )
    {
      foreach($categoryArr[BW_REGEX] as $pattern)
      {
        if ( preg_match('/'.$pattern."/", $string, $matches, PREG_OFFSET_CAPTURE) )
        {
          $MATCH = trim($matches[0][0]);

          $builtRegex = array();
          for ($i=0; $i<strlen($MATCH); $i++) {
            $builtRegex[] = charToRegex($MATCH{$i});
          }
          $builtRegex = implode(BW_NON_WORD_PATTERN.'*', $builtRegex);

          //preg_match('/'.$builtRegex.'/i', $OGString, $NEW_MATCHES, PREG_OFFSET_CAPTURE);
          //$REAL_MATCH = $NEW_MATCHES[0][0];
          preg_match('/'.$builtRegex.'/i', $OGString, $NEW_MATCHES);
          $REAL_MATCH = $NEW_MATCHES[0];

          $badword = new BadWord($REAL_MATCH, $cat);
          return $badword;
        }
      }
    }
  }

  return false;
}

function charToRegex($char)
{
  global $LETTERS;

  $regexPiece = '';

  $regexArray = array();
  foreach($LETTERS as $L1=>$L2)
  {
    if ( $char == $L2 ) {
      $regexArray[] = $L1;
    }
  }

  if ( count($regexArray) ) {
    $regexPiece .= '['.implode('|', $regexArray).'|'.$char.']+';
  }
  else {
    $regexPiece = $char.'+';
  }

  return $regexPiece;
}

function prepareString($string)
{
  // Make it all lower case for speed and simplicity.
  $string = strtolower((string)$string );
  // Replace all numbers with letters.
  $string = numberReplace( $string );

  $string = preg_replace('/'.BW_NON_WORD_PATTERN.'/', '', $string);
  return $string.' ';
}

// replace all numbers that could be letters into their letter version.
function numberReplace( $string )
{
  global $LETTERS;

  foreach($LETTERS as $L1=>$L2) {
    $string = str_replace($L1, $L2, $string);
  }

  return $string;
}



function undoubleLetters($string)
{
  $string_nodoubles = "";
  // Remove double letters
  $string_split_arr = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
  foreach($string_split_arr as $key=>$letter)
  {
    if ( $key == 0 ) {
      $string_nodoubles .= $string_split_arr[$key];
    }
    else if ( strtolower($string_split_arr[$key]) == strtolower($string_split_arr[$key-1]) ) {
      continue;
    }
    else {
      $string_nodoubles .= $string_split_arr[$key];
    }
  }

  if ( $string_nodoubles == "" ) $string_nodoubles = $string;

  return $string_nodoubles;
}

class BadWord
{
  var $badWord,
      $rejectionType;

  function BadWord($word, $type)
  {
    $this->badWord       = $word;
    $this->rejectionType = $type;
  }
}

?>
