<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.
require_once('../../includes/global.inc.php');

if ( !$USER ) {
  die;
}

$ID = (int)$_POST['video_id'];
if ( !$ID ) {
  echo "0";
  die;
}


include(WWW_ROOT.'/includes/video_package/video_func.inc.php');


if ( !ungrabVideo($USER->user_id, $ID) ) {
  echo "0";
  die;
}

echo $ID;
?>