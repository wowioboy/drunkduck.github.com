<?php
$REQUIRE_LOGIN = true;
$ADMIN_ONLY    = false;
$TITLE         = 'Addin Topic';
if ( $_POST['cid'] != 229 ) {
  $REQUIRE_VERIFIED = true;
}
$CONTENT_FILE  = 'community/process_topic.inc.php';
include_once('../template.inc.php');
?>