<?php
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'C&A Line Up Game';
$HEADER_IMG    = 'header_main_games.gif';
$CONTENT_FILE  = 'games/play/_game_page_heart.inc.php';
if ( $_GET['pup'] ) {
  define('NO_TEMPLATE', 1);
}
include_once('../../template.inc.php');
?><img src="http://www.drunkduck.com/track_bug.php" width="1" height="1">