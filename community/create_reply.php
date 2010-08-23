<?php
$REQUIRE_LOGIN = true;
$ADMIN_ONLY    = false;
$TITLE         = 'Reply';
if ( $_GET['cid'] != 229 ) {
  $REQUIRE_VERIFIED = true;
}
$CONTENT_FILE  = 'community/create_reply.inc.php';
include_once('../template.inc.php');
?>