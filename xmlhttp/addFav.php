<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !$USER ) {
  echo "0";
  die;
}

if ( !isset($_GET['fav']) ) return;
$FAV = (int)$_GET['fav'];
$PID = (int)$_GET['p'];

if ( !$FAV ) return;

$res = db_query("SELECT comic_id, user_id, secondary_author, category, comic_name, last_update FROM comics WHERE comic_id='".$FAV."'");
if ( !$COMIC_ROW = db_fetch_object($res) ) { die("0"); }
db_free_result($res);


$res = db_query("SELECT * FROM users WHERE user_id IN ('".$COMIC_ROW->user_id."', '".$COMIC_ROW->secondary_author."')");
while($u = db_fetch_object($res) ) {
  give_trophy( $u->user_id, $u->trophy_string, 9 );
}
db_free_result($res);


db_query("INSERT INTO comic_favs (user_id, comic_id, bookmark_page_id) VALUES ('".$USER->user_id."', '".$FAV."', '".$PID."')");

if ( db_rows_affected() < 1 ) { die("0"); };

$url = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/';
$indicator = '';
if  ( date("Ymd",$COMIC_ROW->last_update) == YMD ) {
  $indicator = ' *';
}
die('<a href="'.$url.'" style="color:white;font-size:11px;"><img src="'.IMAGE_HOST.'/site_gfx_new/genre_icons/'.$COMIC_ROW->category.'.gif" alt="Genre: '.$COMIC_CATS[$COMIC_ROW->category].'" width="12" height="12" align="left" border="0" />'.$COMIC_ROW->comic_name.$indicator.'</a>');
?>