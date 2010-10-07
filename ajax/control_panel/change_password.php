<?php 
require_once('../../includes/db.class.php');
$id = $_POST['id'];
$oldPass = $_POST['oldpass'];
$newPass = $_POST['newpass'];
if ($id && $oldPass && $newPass) {
  $query = "update users 
            set password = '$newPass' 
            where user_id = '$id' 
            and password = '$oldPass'";
  DB::getInstance()->query($query);
}