<?
$REQUIRE_LOGIN = true;
$ADMIN_ONLY    = false;
$TITLE         = 'Friends!';

if ( isset($_GET['u']) ) {
  $TITLE = $_GET['u']."'s friends";
}

$CONTENT_FILE  = 'user/edit_top_friends.inc.php';
include_once('../template.inc.php');
?>