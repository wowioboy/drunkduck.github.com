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

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".md5($CID.'salt').".tar.gz\";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize(WWW_ROOT."/backups/".md5($CID.'salt').".tar.gz"));
readfile(WWW_ROOT."/backups/".md5($CID.'salt').".tar.gz");
?>