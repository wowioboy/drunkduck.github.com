<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.
require_once('../../includes/global.inc.php');

if ( !$USER ) {
  echo "1";
  die;
}

$ID = (int)$_POST['friend_id'];
if ( !$ID ) {
  echo "2";
  die;
}

$res = db_query("SELECT user_id, username FROM users WHERE user_id='".$ID."'");
if ( !$friendRow = db_fetch_object($res) ) {
  echo "3";
  die;
}

db_query("INSERT INTO friends (user_id, friend_id, order_id) VALUES ('".$USER->user_id."', '".$ID."', '0')");
if ( db_rows_affected() < 1 ) {
  echo "4";
  die;
}

include(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');
user_update_trophies( $USER, 19 );

include_once(WWW_ROOT.'/community/message/tikimail_func.inc.php');

send_system_mail('DD Profiles', $friendRow->username, $USER->username.' has added you as a friend!', 'This is just a quick message to let you know that [url=http://user.drunkduck.com/'.$USER->username.']'.$USER->username.'[/url] has added you as a friend!');Z


?>99