<?
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return "BAD";
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);

if ( $_GET['isChap'] ) $CHAP = 1;
else $CHAP = 0;

db_query("UPDATE comic_pages SET is_chapter='".$CHAP."' WHERE page_id='".(int)$_GET['pid']."' AND comic_id='".$COMIC_ROW->comic_id."'");
echo (int)$_GET['pid'];
?>