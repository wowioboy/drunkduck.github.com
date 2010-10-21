<?php
require_once('../includes/db.class.php');

$offset = $_REQUEST['offset'];
$sortCol = $_POST['sort_col'];
$where[] = "total_pages > 0";
$where[] = "created_timestamp != 0";
$where[] = "created_timestamp is not null";
if ($month = $_POST['month']) {
  $where[] = "(date_format(from_unixtime(c.created_timestamp), '%Y-%m') = '$month')";
}
if ($search = $_POST['search']) {
  $where[] = "(c.comic_name like '%$search%' or c.description like '%$search%' or u.username like '%$search%')";
}
foreach ($_POST['filter'] as $column => $filter) {
  $orArray = array();
  foreach ($filter as $value) {
    $orArray[] = "`$column` = '$value'";
  }
  $or = '(' . implode(' or ', $orArray) . ')';
  $where[] = $or;
}
if (is_array($where)) {
  $where = 'where ' . implode(' and ', $where);
}
$limit = $_REQUEST['limit'];
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, from_unixtime(c.created_timestamp) as date
          from comics c 
          inner join users u 
          on u.user_id = c.user_id 
          $where
          order by $sortCol asc 
          limit $offset, $limit";
$featured = DB::getInstance()->fetchAll($query);
foreach ($featured as &$feature) {
  $date = new DateTime($feature['date']);
  $feature['date'] = $date->format('M j Y');
  $feature['description'] = htmlspecialchars($feature['description']);
  $feature['title'] = htmlspecialchars($feature['title']);
}
$query = "select count(1)
          from comics c 
          inner join users u 
          on u.user_id = c.user_id 
          $where";
$count = DB::getInstance()->fetchOne($query);
$array = array('count' => $count, 'featured' => $featured);
echo json_encode($array);