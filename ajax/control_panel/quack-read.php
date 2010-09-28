<?php 
require_once('../../includes/db.class.php');
if ($id = $_POST['mail_id']) {
  $query = "update mailbox set viewed = 1 where mail_id = '$id'";
  DB::getInstance()->query($query);
}
