<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');
require_once('../includes/db.class.php');

if (!$cid = $_GET['cid']) {
  die("Error. Please try again later.");
} 
if (!$pid = $_GET['pid']) {
  die("Error. Please try again later.");
}

$query = "SELECT tag FROM tags_used 
          WHERE comic_id='$cid' 
          AND page_id='$pid' 
          and user_id = '{$USER->user_id}'";
$tags = DB::getInstance()->fetchCol($query);
if ($tags) {
  echo implode(', ', $tags);
} else {
  echo 'none';
}
