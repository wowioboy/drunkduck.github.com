<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../includes/global.inc.php');

$tryName = db_escape_string(ltrim($_POST['try']));
if ( strlen($tryName) < 1 ) die;
if ( strlen($tryName) < 3 ) die;

?>
<ul>
<?
  $res = db_query("SELECT comic_name, description, total_pages FROM comics WHERE comic_name LIKE '".$tryName."%'");
  while ( $row = db_fetch_object($res) ) {
    ?><li id="<?=$row->comic_name?>"><?=$row->comic_name?><span class="informal"><?=htmlentities($row->description, ENT_QUOTES)?><span class="informal_rt"><?=number_format($row->total_pages)?> pages</span></span></li><?
  }
  db_free_result($res);
?>
</ul>