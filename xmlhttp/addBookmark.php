<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !$USER ) {
  echo "0";
  die;
}

if ( !isset($_GET['cid']) ) die("0");
$CID = (int)$_GET['cid'];
$PID = (int)$_GET['p'];

db_query("UPDATE comic_favs SET bookmark_page_id='".$PID."' WHERE user_id='".$USER->user_id."' AND comic_id='".$CID."'");
if ( db_rows_affected() < 1 ) {
  die("UPDATE comic_favs SET bookmark_page_id='".$PID."' WHERE user_id='".$USER->user_id."' AND comic_id='".$CID."'"."\nYou have to make this a favorite before you can bookmark pages!");
}
else {
  die("This page has been saved. Click \"GO\" at any time on this comic to jump to your bookmark.");
}
?>