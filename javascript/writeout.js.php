<?
define('LEAN_AND_MEAN', 1);
include('../includes/global.inc.php');

$res = db_query("SELECT * FROM comic_pages ORDER BY RAND() LIMIT 1");
$PAGE_ROW = db_fetch_object($res);

$res = db_query("SELECT * FROM comics WHERE comic_id='".$PAGE_ROW->comic_id."'");
$COMIC_ROW = db_fetch_object($res);

?>
document.write("<IMG SRC='http://<?=DOMAIN?>/<?=comicNameToFolder($COMIC_ROW->comic_name)?>/pages/<?=md5($COMIC_ROW->comic_id.$PAGE_ROW->page_id)?>.<?=$PAGE_ROW->file_ext?>'>");