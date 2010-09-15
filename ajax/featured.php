<?php
require_once('../includes/db.class.php');

$offset = $_REQUEST['offset'];
$search = $_REQUEST['search'];
if ($search) {
  $search = "and (c.comic_name like '%$search%' or c.description like '%$search%' or u.username like '%$search%')";
}
$query = "select c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, count(l.page_id) as likes
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
$search
group by c.comic_name
order by f.feature_id desc 
limit $offset, 10";
$featured = DB::getInstance()->fetchAll($query);
$query = "select count(1)
from comics c 
inner join featured_comics f 
on f.comic_id = c.comic_id 
inner join users u 
on u.user_id = c.user_id 
where f.approved = '1' 
$search";
$count = DB::getInstance()->fetchOne($query);
$array = array('count' => $count, 'featured' => $featured);
echo json_encode($array);