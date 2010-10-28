<?php
require_once('../includes/db.class.php');
$userId = $_GET['user_id'];
$query = "select u.user_id, u.username, avatar_ext
          from users u 
          inner join friends f 
          on f.friend_id = u.user_id 
          where f.user_id = '$userId' 
          limit $offset, 10";  
$friends = DB::getInstance()->fetchAll($query);
echo json_encode($friends);
