<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');


$REQUEST = isset($_POST['request']) ? $_POST['request'] : $_GET['request'];
if ( strstr($REQUEST, '..') ) die;

list($REQ, $EXTRA) = explode('[1]', $REQUEST);

include_once('content/'.$REQ.'.inc.php');


?>
