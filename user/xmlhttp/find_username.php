<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');

$tryName = trim(db_escape_string( $_POST['try'] ));
if ( strlen($tryName) < 1 ) die;

?>
<ul>
<?
  $res = db_query("SELECT username, last_seen FROM users WHERE username LIKE '".$tryName."%' LIMIT 10");
  while ( $row = db_fetch_object($res) ) {
    ?><li id="<?=$row->username?>"><?=$row->username?><span class="informal"><span class="informal_rt">Last Seen: <?=date("n-d-Y", $row->last_seen)?></span></span></li><?
  }
  db_free_result($res);
?>
</ul>