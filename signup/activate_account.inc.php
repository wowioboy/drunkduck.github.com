<?
require_once('signup_data.inc.php');
require_once(PACKAGES.'/wordfilter_package/load.inc.php');

if ( !isset($_GET['u']) || !isset($_GET['c']) )
{
  return;
}


if ( $_GET['c'] == md5(strtolower($_GET['u']).'tikiverification') )
{
  $res = db_query("UPDATE users SET flags=(flags|".FLAG_VERIFIED.") WHERE username='".db_escape_string($_GET['u'])."'");
  if ( db_rows_affected($res) == 1 ) {
    echo "<DIV ALIGN='CENTER'><B>Your account has been verified!</DIV>";
  }
  else {
    echo "<DIV ALIGN='CENTER'><B>There was an error in your request, or your account has already been validated!</DIV>";
  }
}

?>