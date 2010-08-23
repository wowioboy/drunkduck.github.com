<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.

include_once('../../includes/global.inc.php');

if ( !$USER ) {

  ?>alert('You are not logged in');<?
  return;

}

$ID = (int)$_POST['id'];


db_query("DELETE FROM publisher_links WHERE id='".$ID."' AND user_id='".$USER->user_id."'");
if ( db_rows_affected() < 0 ) {
  ?>alert('Error: Could not delete link.');<?
  return;
}

?>$('link_<?=$ID?>').style.display='none';