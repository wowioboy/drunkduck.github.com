<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( !$USER ) return "noop";

if ( !isset($_GET['fav']) ) return;
$FAV = (int)$_GET['fav'];

$res = db_query("DELETE FROM comic_favs WHERE user_id='".$USER->user_id."' AND comic_id='".$FAV."'");
echo $FAV;
?>
