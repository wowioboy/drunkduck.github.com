<?
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'User Lookup!';
if ( isset($_GET['u']) ) $TITLE .= " (".$_GET['u'].")";
$CONTENT_FILE  = 'user_lookup.inc.php';
include_once('template.inc.php');
?>