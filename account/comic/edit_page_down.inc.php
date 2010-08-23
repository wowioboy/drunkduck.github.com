<?
include('header_edit_comic.inc.php');

if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];
if ( !isset($_GET['pid'] ) ) return;
$PID = (int)$_GET['pid'];

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);
$FOLDER_NAME = str_replace(" ", "_", $COMIC_ROW->comic_name);


$res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$CID."' AND page_id='".$PID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$PAGE_ROW = db_fetch_object($res);
db_free_result($res);
?>

<DIV CLASS='container' ALIGN='LEFT'>
  
  Editing of comic details has been temporarily disabled. This functionality should be fully restored by Monday, 12/14/2009 @ 8:00 AM PST
  <br/><br />
   Sorry for the inconvenience.
</DIV>
<?






include('footer_edit_comic.inc.php');
?>