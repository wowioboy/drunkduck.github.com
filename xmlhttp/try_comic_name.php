<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

if ( isset($_GET['try']) )
{
  $tryName = trim(db_escape_string( $_GET['try'] ));

  if ( strlen($tryName) < 1 ) die;
  if ( strlen($tryName) < 3 ) die("Must be 3 or more characters.");

  if ( preg_match('/([^a-zA-Z0-9 ])+/', $tryName) ) {
    die("Only Letters, Numbers, and SPACES are permitted.");
  }

  $res = db_query("SELECT comic_name FROM comics WHERE comic_name='".$tryName."'");
  if ( db_num_rows($res) > 0 ) {
    db_free_result($res);
    die("The name \"".$tryName . "\" is already taken.");
  }
  db_free_result($res);


  $tryName = str_replace(' ', '_', $tryName);
  if ( file_exists(WWW_ROOT.'/'.strtolower($tryName)) ||  file_exists(WWW_ROOT.'/'.$tryName) || file_exists(WWW_ROOT.'/comics/'.$tryName{0}.'/'.$tryName) ) {
    die("\"".$tryName . "\" is a taken or reserved name.");
  }



}
?>
This name is available!