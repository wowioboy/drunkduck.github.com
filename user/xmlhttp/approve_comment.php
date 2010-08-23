<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.
require_once('../../includes/global.inc.php');

if ( !$USER ) {
  echo "0";
  die;
}

$ID = (int)$_POST['comment_id'];
if ( !$ID ) {
  echo "0";
  die;
}

db_query("UPDATE profile_comments SET approved='1' WHERE id='".$ID."' AND user_id='".$USER->user_id."'");
if ( db_rows_affected() < 1 ) {
  echo "0";
  die;
}

echo $ID;
?>