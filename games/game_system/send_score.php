<?
define('DEBUG_MODE', 0);

require_once('game_system_data.inc.php');
require_once('game_system_func.inc.php');

include_once('../../includes/global.inc.php');

if ( !$USER ) {
  die('success=0&msg='.rawurlencode('You are <B>not</B> logged in to DrunkDuck.com, so your score will not be recorded.'));
}

$PAYLOAD = $_POST['payload'];
if ( !isset($_POST['payload']) ) {
  $PAYLOAD = $_GET['payload'];
}

$PASSBACK = $_POST['passBack'];
if ( !isset($_POST['passBack']) ) {
  $PASSBACK = $_GET['passBack'];
}

// Reverse string.
$NEW_PAYLOAD = '';
for($i=strlen($PAYLOAD)-1; $i>=0; $i--) {
  $NEW_PAYLOAD .= $PAYLOAD{$i};
}
$PAYLOAD = $NEW_PAYLOAD;

// First get the length of the session id
//$PARSED['SESSION_ID_LENGTH'] = (int)substr($PAYLOAD, 0, 2);
//$PAYLOAD = substr($PAYLOAD, 2);
$PARSED['SESSION_ID_LENGTH'] = strip_chars($PAYLOAD, 0, 2);

$NEW_PAYLOAD = '';
for($i=0; $i<(strlen($PAYLOAD)/3); $i++) {
  $NEW_PAYLOAD .= chr( ltrim(substr($PAYLOAD, $i*3, 3),"0") );
}
$PAYLOAD = $NEW_PAYLOAD;


// Now get the actual session_id:
//$PARSED['SESSION_ID'] = (int)substr($PAYLOAD, 0, $PARSED['SESSION_ID_LENGTH']);
//$PAYLOAD = substr($PAYLOAD, $PARSED['SESSION_ID_LENGTH']);
$PARSED['SESSION_ID'] = strip_chars($PAYLOAD, 0, $PARSED['SESSION_ID_LENGTH']);

if ( !$SESS_ROW = has_session($USER, $PARSED['SESSION_ID']) ) {
  die("success=0&msg=".rawurlencode('INVALID SESSION, '.$USER->username.' [Sess ID# '.$PARSED['SESSION_ID'].'].<BR>Please try re-launching the game.'));
}
$game_id = $SESS_ROW->game_id;

// Now get the checksum:
//$PARSED['SESSION_CHECKSUM'] = substr($PAYLOAD, 0, 32);
//$PAYLOAD = substr($PAYLOAD, 32);
$PARSED['SESSION_CHECKSUM'] = strip_chars($PAYLOAD, 0, 32);

// Now get the md5() of the original score+session_ck
// $PARSED['SCORE_MD5'] = substr($PAYLOAD, 0, 32);
// $PAYLOAD = substr($PAYLOAD, 32);
$PARSED['SCORE_MD5'] = strip_chars($PAYLOAD, 0, 32);

// Now get the score:
$PARSED['SCORE'] = $PAYLOAD / $PARSED['SESSION_ID_LENGTH'];

if ( ($PARSED['SESSION_CHECKSUM'] == md5($PARSED['SESSION_ID'].SESSION_CHECKSUM_SALT)) && ($PARSED['SCORE_MD5'] == md5($PARSED['SCORE'].$PARSED['SESSION_ID'])) ) {
  $SUCCESS = 1;
  $MSG = "Your score has been submitted!";
}
else {
  $SUCCESS = 0;
  $MSG = "Invalid Data.";
}

$EARNED = 0;
if ( ($SUCCESS == 1) && ($PARSED['SCORE'] > 0) )
{
  // Grant tiki tokens
  // Also handle alignment adjustment.

  $res = db_query("SELECT * FROM game_info WHERE game_id='".$SESS_ROW->game_id."'");
  if ($row = db_fetch_object($res) )
  {
    $EARNED = floor( $row->pts_to_tokens * $PARSED['SCORE'] );
    if ( $EARNED > 1000 ) $EARNED = 1000;
    db_query("UPDATE users SET duckbills=duckbills+".$EARNED." WHERE user_id='".$USER->user_id."'");

    if ( $PARSED['SCORE'] > $row->score_to_beat ) {
      if ( $row->trophy_beat_score ) {
        give_trophy( $USER->user_id, $USER->trophy_string, $row->trophy_beat_score );
      }
    }

  }
  db_free_result($res);


  // Bump the session
  bump_session($USER, $PARSED['SESSION_ID']);

  // Record the score
  $res = db_query("SELECT * FROM user_highscores WHERE game_id='".$game_id."' AND username='".$USER->username."'");
  if ( $row = db_fetch_object($res) ) {
    if ( $row->highscore <= $PARSED['SCORE'] ) {
      db_query("DELETE FROM user_highscores WHERE game_id='".$game_id."' AND username='".$USER->username."'");
      db_query("INSERT INTO user_highscores (game_id, username, highscore) VALUES ('".$game_id."', '".$USER->username."', '".$PARSED['SCORE']."')");
    }
  }
  else {
    db_query("INSERT INTO user_highscores (game_id, username, highscore) VALUES ('".$game_id."', '".$USER->username."', '".$PARSED['SCORE']."')");
  }
  db_free_result($res);




  // Find out how many entries currently exist...
  $res = db_query("SELECT COUNT(*) as entries FROM highscore_top_100 WHERE game_id='".$game_id."'");
  $row = db_fetch_object($res);
  $ENTRIES = $row->entries;
  db_free_result($res);



  $res = db_query("SELECT * FROM highscore_top_100 WHERE game_id='".$game_id."' AND username='".$USER->username."'");
  if ( $row = db_fetch_object($res) )
  {
    db_free_result($res);

    if ( $row->highscore < $PARSED['SCORE'] ) {
      // Just update their position ( score )
      db_query("UPDATE highscore_top_100 SET highscore='".$PARSED['SCORE']."', unix_time='".time()."' WHERE game_id='".$game_id."' AND username='".$USER->username."'");
    }
  }
  else
  {
    db_free_result($res);

    // See if they get free entry
    if ( $ENTRIES < 100 ) {
      db_query("INSERT INTO highscore_top_100 (game_id, username, highscore, unix_time) VALUES ('".$game_id."', '".$USER->username."', '".$PARSED['SCORE']."', '".time()."')");
    }
    else {
      // See if they beat anyone.
      $res = db_query("SELECT username, highscore FROM highscore_top_100 WHERE game_id='".$game_id."' AND highscore<".$PARSED['SCORE']." ORDER BY highscore ASC LIMIT 1");
      if ( $row = db_fetch_object($res) )
      {
        db_query("DELETE FROM highscore_top_100 WHERE game_id='".$row->game_id."' AND username='".$row->username."' AND highscore='".$row->highscore."'");
        db_query("INSERT INTO highscore_top_100 (game_id, username, highscore, unix_time) VALUES ('".$game_id."', '".$USER->username."', '".$PARSED['SCORE']."', '".time()."')");
      }
      db_free_result($res);
    }
  }

  // Was there "Pass Back" data?
  if ( $PASSBACK )
  {
    // Is the passback a challenge?
    if ( substr($PASSBACK, 0, 5) == 'CHALL' )
    {
      $CHALL = explode("=", $PASSBACK);
      $challenge_id = (int)$CHALL[1];

      $res = db_query("SELECT * FROM challenges WHERE challenge_id='".$challenge_id."' AND end_timestamp>='".time()."'");
      if ( $row = db_fetch_object($res) )
      {
        // Is it an official started and accepted contest?
        if ( $row->user1_accept && $row->user2_accept )
        {
          // Is the user user1?
          if ( $row->user1 == $USER->username )
          {
            // Better score?
            if ( $row->user1_score < $PARSED['SCORE'] ) {
              db_query("UPDATE challenges SET user1_score='".(int)$PARSED['SCORE']."' WHERE challenge_id='".$challenge_id."'");
            }
          } // Is the user user2?
          else if ( $row->user2 == $USER->username )
          {
            // Better score?
            if ( $row->user2_score < $PARSED['SCORE'] ) {
              db_query("UPDATE challenges SET user2_score='".(int)$PARSED['SCORE']."' WHERE challenge_id='".$challenge_id."'");
            }
          }
        }
      }
      db_free_result($res);
    }
  }
}

//$MSG .= "<BR><BR><P ALIGN='CENTER'>You earned ".number_format($EARNED)." Duck Bills!<BR>You now have ".number_format($USER->duckbills+$EARNED)." Duck Bills on hand.</P>";
echo "success=".$SUCCESS."&msg=".rawurlencode($MSG);

?>