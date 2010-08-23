<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
define('LEAN_AND_MEAN', 1);
include_once('../includes/global.inc.php');

$res = db_query("SELECT * FROM comics ORDER BY last_update DESC LIMIT 1");
$row = db_fetch_object($res);
echo $row->comic_name;
?>