<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);

$FOLDER_NAME = str_replace(' ', '_', $COMIC_ROW->comic_name);

if ( $_GET['make18'] && !($COMIC_ROW->flags&FLAG_ADULT) ) {
  $COMIC_ROW->flags = toggleFlag( $COMIC_ROW->flags, FLAG_ADULT);
}
else if ( $COMIC_ROW->flags&FLAG_ADULT ) {
  $COMIC_ROW->flags = toggleFlag( $COMIC_ROW->flags, FLAG_ADULT);
}
db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");



if ( !($COMIC_ROW->flags&FLAG_ADULT) ) {
  echo "Any";
}
else {
  echo "<A HREF=\"JavaScript:makeUnder18();\">Any</A>";
}
echo " | ";
if ( $COMIC_ROW->flags&FLAG_ADULT ) {
  echo "18+";
}
else {
  echo "<A HREF=\"JavaScript:make18();\">18+</A>";
}
?>
