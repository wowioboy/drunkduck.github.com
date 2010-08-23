<?php
header("Content-type: application/xml");
define('DEBUG_MODE', 0); // keep debug info from polluting response.
Header("P3P: CP='NOI DSP NID TAIo PSAa OUR IND UNI OTC TST'");
include_once('../includes/global.inc.php');

// USAGE:
require_once("../rss/class.easyrss.php");
$rss = new easyRSS;

$rss_array = array(
                   "encoding"=>"utf-8",
                   "language"=>"en-us",
                   "title"=>"DrunkDuck.com Games", // This field is mandatory
                   "description"=>"A list of DrunkDuck Games to pull.", // This field is mandatory
                   "link"=>"http://www.drunkduck.com/games/", // This field is mandatory
                   "items"=>array()
                  );

$res = db_query("SELECT * FROM game_info WHERE is_live='1' ORDER BY game_id DESC");
while ($row = db_fetch_object($res))
{
  $PLAY_PAGE = 'http://'.DOMAIN.'/games/play/'.str_replace(" ", "_", $row->title).'.php?play=1';
  $PLAY_PAGE .= '&pup=1'; // prevents template etc.

  $rss_array["items"][] = array(
                                "title"=>urlencode($row->title), // This field is mandatory
                                "description"=>'<a href="#" onClick="launchGame(\''.urlencode($PLAY_PAGE).'\', '.$row->width.', '.$row->height.');return false;"><img src="http://images.drunkduck.com/games/thumbnails/game_'.$row->game_id.'_tn_med.gif" width="100" height="68" border="0" /></a>', // This field is mandatory
                                "pubDate"=>time(), // pubDate MUST BE the unix timestamp
                                "link"=>urlencode($PLAY_PAGE)
                               );
}

echo $rss->rss($rss_array);
?>