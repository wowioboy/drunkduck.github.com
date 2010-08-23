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

$PATH      = WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME;
$TAR_PATH = WWW_ROOT.'/backups/'.md5($CID.'salt').'.tar';
$ZIP_PATH  = $TAR_PATH .'.gz';


if ( !file_exists($ZIP_PATH) || ((time()-filemtime($ZIP_PATH)) < 3600 ) )
{
  unlink($TAR_PATH);
  unlink($ZIP_PATH);
  // [17:28] TNT JRice: tar cvf foo.tar *
  // [17:28] TNT JRice: gzip foo.tar
  
  /*  12.158.190.118
  cd /homepages/27/d145328821/htdocs/drunkduck.com/comics/T/Test/
  tar cvf /homepages/27/d145328821/htdocs/drunkduck.com/backups/test.tar gfx html pages
  */
  
  
  exec("cd $PATH;tar cvf $TAR_PATH gfx html pages");
  if ( !file_exists($TAR_PATH) ) {
    echo "There was an error. Please contact an administrator.";
    return;
  }
  
  exec("gzip $TAR_PATH");
  if ( !file_exists($ZIP_PATH) ) {
    unlink($TAR_PATH);
    echo "There was an error. Please contact an administrator.";
    return;
  }
}
echo "<A HREF='http://".DOMAIN."/xmlhttp/get_backup.php?cid=".$COMIC_ROW->comic_id."'>http://".DOMAIN."/backups/".md5($CID.'salt').".tar.gz</A>";
?>
