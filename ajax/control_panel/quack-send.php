<?php 
require_once('../../includes/db.class.php');
if ($username = $_REQUEST['username']) {
  $to = $_REQUEST['to'];
  $subject = stripslashes($_REQUEST['subject']);
  $message = stripslashes($_REQUEST['message']);
  $time = time();
  $query = "insert into mailbox 
            (username_to, username_from, title, message, time_sent, viewed) 
            values 
            ('$to', '$username', '$subject', '$message', '$time', '0')";
  DB::getInstance()->query($query);
}
