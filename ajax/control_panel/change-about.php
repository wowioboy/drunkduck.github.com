<?php 
require_once('../../includes/db.class.php');
$id = $_POST['user_id'];
$about = $_POST['about_self'];
if ($id && $about) {
  $query = "update users about_self 
            set about_self = '$about' 
            where user_id = '$id'";
  DB::getInstance()->query($query);
}