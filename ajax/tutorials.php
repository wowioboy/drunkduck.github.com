<?php
require_once('../includes/db.class.php');

$offset = $_REQUEST['offset'];
if ($month = $_REQUEST['month']) {
  $where[] = "(date_format(from_unixtime(timestamp), '%Y-%m') = '$month')";
}
if ($search = $_REQUEST['search']) {
  $where[] = "(title like '%$search%' or description like '%$search%' or username like '%$search%')";
}
if (is_array($where)) {
  $where = ' and ' . implode(' and ', $where);
}
$limit = $_REQUEST['limit'];
$query = "select tutorial_id as id, title, username as author, description, from_unixtime(timestamp) as timestamp
from tutorials
where finalized = '1' 
$where
order by timestamp desc 
limit $offset, $limit";
$featured = DB::getInstance()->fetchAll($query);
foreach ($featured as &$feature) {
  $date = new DateTime($feature['timestamp']);
  $feature['timestamp'] = $date->format('F j Y');
}
$query = "select count(1)
from tutorials 
where finalized = '1' 
$where";
$count = DB::getInstance()->fetchOne($query);
$array = array('count' => $count, 'featured' => $featured);
echo json_encode($array);