<?
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'Friends!';

if ( isset($_GET['u']) ) {
  $TITLE = $_GET['u']."'s friends";
}

$CONTENT_FILE  = 'user/friends.inc.php';
include_once('../template.inc.php');
?>