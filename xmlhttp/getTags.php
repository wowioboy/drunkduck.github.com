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

$query = "SELECT tag FROM tags_by_page WHERE comic_id='$cid' AND page_id='$pid' ORDER BY counter DESC LIMIT 10";
$tags = DB::getInstance()->fetchCol($query);
if ($tags) {
  foreach ($tags as $tag) {
    echo "<A HREF='http://".DOMAIN."/search.php?cid=".$cid."&tag=".rawurlencode($tag)."' style='font-weight:bold;color: #FFCC00;text-decoration: underline;'>".$tag."</A> ";
  }
} else {
  echo 'none';
}
