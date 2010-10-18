<?php
require_once('../includes/db.class.php');
require_once('../bbcode.php'); 

$offset = $_REQUEST['offset'] ? $_REQUEST['offset'] : '0';
if ($month = $_REQUEST['month']) {
  $where[] = "(date_format(from_unixtime(b.timestamp_date), '%Y-%m') = '$month')";
}
if ($search = $_REQUEST['search']) {
  $where[] = "(b.title like '%$search%' or b.body like '%$search%' or u.username like '%$search%')";
}
if (is_array($where)) {
  $where = 'where ' . implode(' and ', $where);
}
$query = "select b.title, u.username as author, b.body, from_unixtime(b.timestamp_date) as created_on
from admin_blog b 
inner join users u 
on u.user_id = b.user_id 
$where
order by b.timestamp_date desc 
limit $offset, 10";
$news = DB::getInstance()->fetchAll($query);
foreach ($news as &$item) {
    $item['body'] = stripslashes(bbcode2html($item['body']));
    $date = new DateTime($item['created_on']); 
    $item['created_on'] = $date->format('F j, Y - g:ia');
}
$query = "select count(1)
from admin_blog b 
inner join users u 
on u.user_id = b.user_id 
$where
order by b.timestamp_date desc";
$count = DB::getInstance()->fetchOne($query);
$array = array('count' => $count, 'news' => $news);
echo json_encode($array);