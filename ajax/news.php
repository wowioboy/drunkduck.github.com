<?php
require_once('../includes/db.class.php');

$offset = $_REQUEST['offset'];
$search = $_REQUEST['search'];
if ($search) {
  $search = "where b.title like '%$search%' or b.body like '%$search%' or u.username like '%$search%'";
}
$query = "select b.title, u.username as author, b.body, from_unixtime(b.timestamp_date) as created_on
from admin_blog b 
inner join users u 
on u.user_id = b.user_id 
$search
order by b.timestamp_date desc 
limit $offset, 5";
$news = DB::getInstance()->fetchAll($query);
$query = "select count(1)
from admin_blog b 
inner join users u 
on u.user_id = b.user_id 
$search
order by b.timestamp_date desc";
$count = DB::getInstance()->fetchOne($query);
$array = array('count' => $count, 'news' => $news);
echo json_encode($array);