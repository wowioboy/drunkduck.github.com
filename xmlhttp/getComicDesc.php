<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
define('LEAN_AND_MEAN', 1);
include_once('../includes/global.inc.php');

if ( isset($_GET['try']) )
{
  $tryName = trim(db_escape_string( $_GET['try'] ));
  
  $res = db_query("SELECT description FROM comics WHERE comic_name='".$tryName."'");
  $row = db_fetch_object($res);
  echo $row->description;
}
?>