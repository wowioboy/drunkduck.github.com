<?php
require_once('../includes/db.class.php');

$offset = $_REQUEST['offset'];
if ($month = $_REQUEST['month']) {
  $where[] = "(concat(substring(ymd_date_live, 1, 4), '-', substring(ymd_date_live, 5, 2)) = '$month')";
}
if ($search = $_REQUEST['search']) {
  $where[] = "(c.comic_name like '%$search%' or c.description like '%$search%' or u.username like '%$search%')";
}
if (is_array($where)) {
  $where = ' and ' . implode(' and ', $where);
}
$limit = $_REQUEST['limit'];
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, count(l.page_id) as likes, from_unixtime(c.created_timestamp) as date
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
inner join users u 
on u.user_id = c.user_id 
left join comic_pages p 
on c.comic_id = p.comic_id 
left join page_likes l 
on p.page_id = l.page_id
where f.approved = '1' 
$where
group by c.comic_name
order by f.ymd_date_live desc 
limit $offset, $limit";
$featured = DB::getInstance()->fetchAll($query);
foreach ($featured as &$feature) {
  $date = new DateTime($feature['date']);
  $feature['date'] = $date->format('M j Y');
}
$query = "select count(1)
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
inner join users u 
on u.user_id = c.user_id 
where f.approved = '1' 
$where";
$count = DB::getInstance()->fetchOne($query);
$array = array('count' => $count, 'featured' => $featured);
echo json_encode($array);