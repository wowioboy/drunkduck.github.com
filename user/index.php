<?
$REQUIRE_LOGIN = false;
$ADMIN_ONLY    = false;
$TITLE         = 'User Lookup!';

if ( isset($_GET['u']) )
{
  if ( substr($_GET['u'], 0, 5) == 'user/' ) {
    $_GET['u'] = substr( $_GET['u'], 5 );
  }
  if ( $_GET['u']{strlen($_GET['u'])-1} == '/' ) {
    $_GET['u'] = substr( $_GET['u'], 0, strlen($_GET['u'])-1);
  }
}
if ( isset($_GET['u']) ) $TITLE .= " (".$_GET['u'].")";

$CONTENT_FILE  = 'user/index.inc.php';
include_once('../template.inc.php');
?>