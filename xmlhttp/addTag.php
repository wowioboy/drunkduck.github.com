<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !$USER ) {
  echo "You must be logged in to tag pages!";
  die;
}

if ( !isset($_GET['tag']) ) return;
$TAG = $_GET['tag'];
if ( !$TAG ) die("You didn't enter a tag!");

if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];
if ( !$CID ) die("Error. Please try again later.");

if ( !isset($_GET['pid']) ) return;
$PID = (int)$_GET['pid'];
if ( !$PID ) die("Error. Please try again later.");

$TAGS = preg_split("`[^a-zA-Z0-9_\'\- ]`", $TAG, -1, PREG_SPLIT_NO_EMPTY);

foreach($TAGS as $t)
{
  $t = trim($t);
  if ( (strlen($t) <= 50) && (strlen($t) > 2) )
  {
    $t = db_escape_string($t);
    db_query("INSERT INTO tags_used (user_id, tag, comic_id, page_id) VALUES ('".$USER->user_id."', '".$t."', '".$CID."', '".$PID."')");
    if ( db_rows_affected() > 0 )
    {
      db_query("UPDATE tags_by_page SET counter=counter+1 WHERE tag='".$t."' AND comic_id='".$CID."' AND page_id='".$PID."'");
      if ( db_rows_affected() < 1 ) {
        db_query("INSERT INTO tags_by_page (tag, comic_id, page_id, counter) VALUES ('".$t."', '".$CID."', '".$PID."', '1')");
      }

      db_query("UPDATE tags_by_comic SET counter=counter+1 WHERE tag='".$t."' AND comic_id='".$CID."'");
      if ( db_rows_affected() < 1 ) {
        db_query("INSERT INTO tags_by_comic (tag, comic_id, counter) VALUES ('".$t."', '".$CID."', '1')");
      }

      db_query("UPDATE tags_counter_daily SET counter=counter+1 WHERE tag='".$t."' AND ymd_date='".date("Ymd")."'");
      if ( db_rows_affected() < 1 ) {
        db_query("INSERT INTO tags_counter_daily (tag, counter, ymd_date) VALUES ('".$t."', '1', '".date("Ymd")."')");
      }

      db_query("UPDATE tags_counter_all_time SET counter=counter+1 WHERE tag='".$t."'");
      if ( db_rows_affected() < 1 ) {
        db_query("INSERT INTO tags_counter_all_time (tag, counter) VALUES ('".$t."', '1')");
      }
    }
  }
}
?>
