<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !$USER ) {
  echo "0";
  die;
}

if ( !isset($_GET['cid']) ) die("0");
$CID = (int)$_GET['cid'];

db_query("UPDATE comic_favs SET email_on_update='1' WHERE user_id='".$USER->user_id."' AND comic_id='".$CID."'");
if ( db_rows_affected() < 1 ) {
  die("Oops! Make this comic a favorite first.");
}
else {
  die("You will be notified by email when this comic updates.");
}
?>