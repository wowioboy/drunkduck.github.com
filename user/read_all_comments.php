<?
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'Comments!';

if ( isset($_GET['u']) ) {
  $TITLE = $_GET['u']."'s Comments";
}

$CONTENT_FILE  = 'user/read_all_comments.inc.php';
include_once('../template.inc.php');
?>