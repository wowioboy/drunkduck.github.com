<?
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'Videos!';

if ( isset($_GET['u']) ) {
  $TITLE = $_GET['u']."'s Videos";
}

$CONTENT_FILE  = 'user/see_all_videos.inc.php';
include_once('../template.inc.php');
?>