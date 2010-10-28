<?php
require_once('../includes/db.class.php');
$userId = $_GET['user_id'];
$query = "select u.user_id, u.username, avatar_ext
          from users u 
          inner join friends f 
          on f.friend_id = u.user_id 
          where f.user_id = '$userId'";  
$friends = $db->fetchAll($query);
echo json_encode();
