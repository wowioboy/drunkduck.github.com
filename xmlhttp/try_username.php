<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');
include_once(PACKAGES.'/wordfilter_package/load.inc.php');

if ( isset($_GET['try']) )
{
  $tryName = trim(db_escape_string( $_GET['try'] ));
  
  if ( strlen($tryName) < 1 ) die;
  if ( strlen($tryName) < 3 ) die("Must be 3 or more characters.");
  
  $res = db_query("SELECT username FROM users WHERE username='".$tryName."'");
  if ( db_num_rows($res) > 0 ) {
    db_free_result($res);
    die("The username \"".$tryName . "\" is already taken.");
  }
  db_free_result($res);
  
  if ( is_package_loaded('wordfilter') ) {
    if ( $returned = doBadWordCheck($tryName) )
    {
      die("The name you chose is inappropriate");
    }
  }
}
?>
This name is available!