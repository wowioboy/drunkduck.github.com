<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !$USER || !($USER->flags & FLAG_IS_ADMIN) ) {
  echo "0";
  die;
}

if ( !isset($_GET['cid']) ) die("0");
$CID = (int)$_GET['cid'];

db_query("UPDATE comics SET rating_questionable='1' WHERE comic_id='".$CID."'");
?>