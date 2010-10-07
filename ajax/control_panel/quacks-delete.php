<?php 
require_once('../../includes/db.class.php');
if ($username = $_POST['username']) {
  if($_POST['quacks']) {
    $quacks = implode(',', $_POST['quacks']);
    $query = "delete from mailbox where mail_id in ($quacks) and (username_to = '$username' or username_from = '$username')";
    $db->query($query);
  }
}