<?php
$REQUIRE_LOGIN = true;
$ADMIN_ONLY    = false;
$TITLE         = 'Create Topic';
$CONTENT_FILE  = 'community/create_topic.inc.php';
if ( $_GET['cid'] != 229 ) {
  $REQUIRE_VERIFIED = true;
}
include_once('../template.inc.php');
?>