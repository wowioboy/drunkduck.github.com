<?php 
require_once('../../includes/db.class.php');
$id = $_POST['id'];
$email = $_POST['email'];
if ($id && $email) {
  $query = "update demographics 
            set email = '$email' 
            where user_id = '$id'";
  DB::getInstance()->query($query);
}