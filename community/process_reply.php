<?php
$REQUIRE_LOGIN = true;
$ADMIN_ONLY    = false;
$TITLE         = 'Sending Reply';
if ( $_POST['cid'] != 229 ) {
  $REQUIRE_VERIFIED = true;
}
$CONTENT_FILE  = 'community/process_reply.inc.php';
include_once('../template.inc.php');
?>