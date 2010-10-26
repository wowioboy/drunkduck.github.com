<?php
require_once('../includes/db.class.php');
$username = $_GET['username'];
$query = "select count(1) 
          from mailbox 
          where username_to = '$username' 
          and username_from is not null 
          and viewed = '0'";
$result = DB::getInstance()->fetchOne($query);
echo $result;