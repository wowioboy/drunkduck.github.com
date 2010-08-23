<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];
if ( !$CID ) die("Error. Please try again later.");

if ( !isset($_GET['pid']) ) return;
$PID = (int)$_GET['pid'];
if ( !$PID ) die("Error. Please try again later.");


$res = db_query("SELECT * FROM tags_by_page WHERE comic_id='".$CID."' AND page_id='".$PID."' ORDER BY counter DESC LIMIT 10");
if ( db_num_rows($res) == 0 ) echo "<b>None</b>";
while( $row = db_fetch_object($res) ) {
  echo "<A HREF='http://".DOMAIN."/search.php?cid=".$CID."&tag=".rawurlencode($row->tag)."' style='font-weight:bold;color: #FFCC00;text-decoration: underline;'>".$row->tag."</A> ";
}
db_free_result($res);

?>
