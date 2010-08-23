<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../../includes/global.inc.php');

$date = (int)$_POST['date'];

list($y, $m, $d) = sscanf($date, '%4d%2d%2d');

if ( !$y || !$m || !$d )
{
  ?>No data provided.<?
  die;
}

echo $m . ' ' . $d . ', '.$y;

?>