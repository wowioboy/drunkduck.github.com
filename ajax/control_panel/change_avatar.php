<?php 
require_once('../../includes/db.class.php');
/*$fp = fopen('../../filearray.txt', 'w');*/
/*fwrite($fp, var_export($_FILES, true));*/
/*fclose($fp);*/
/*$id = $_POST['id'];
$email = $_POST['email'];
if ($id && $email) {
  $query = "update demographics 
            set email = '$email' 
            where user_id = '$id'";
  DB::getInstance()->query($query);
}*/

// PUT THE FILE WHERE IT NEEDS TO GO
$_FILES['avatar'];
