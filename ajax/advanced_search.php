<?php
require_once('../includes/db.class.php');

$offset = $_REQUEST['offset'];
$sortCol = $_POST['sort_col'];
$sortDir = $_POST['sort_dir'];
$where[] = "created_timestamp != 0";
$where[] = "created_timestamp is not null";
/*
if ($month = $_POST['month']) {
  $where[] = "(date_format(from_unixtime(c.created_timestamp), '%Y-%m') = '$month')";
} */
if ($search = $_POST['search']) {
  $where[] = "(c.comic_name like '%$search%' or c.description like '%$search%')";
}

# PAGES
$pages = $_POST['pages'];
if ($pages == 'range') {
  $pageMin = $_POST['page_min'];
  $pageMax = $_POST['page_max'];
  if ($pageMin && $pageMax) {
    $where[] = "(c.total_pages between $pageMin and $pageMax)";
  } else if ($pageMin) {
    $where[] = "(c.total_pages >= $pageMin)";
  } else {
    $where[] = "(c.total_pages <= $pageMax)";
  }
} else {
  $where[] = "(c.total_pages >= $pages)";
}

# UPDATES 
$update = $_POST['update'];
if ($update != 'any') {
  $now = new DateTime();
  $date = new DateTime();
  if ($update == 'week') {
    $date->modify('-1 day');
    $now = $now->format('Y-m-d 23:59:59');
    $date = $date->format('Y-m-d 00:00:00');
    $where[] = "(from_unixtime(last_update) between '$date' and '$now')";
  } else if ($update == 'month') {
    $date->modify('-1 month');
    $now = $now->format('Y-m-d 23:59:59');
    $date = $date->format('Y-m-d 00:00:00');
    $where[] = "(from_unixtime(last_update) between '$date' and '$now')";
  } else if ($update == 'today') {
    $date = $date->format('Y-m-d 00:00:00');
    $where[] = "(from_unixtime(last_update) >= '$date')"; 
  }
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
$query = "select c.comic_id as id, c.comic_name as title, u.username as author, c.rating_symbol as rating, c.total_pages as pages, c.description, from_unixtime(if(c.last_update = 0, c.created_timestamp, c.last_update)) as date
          from comics c 
          inner join users u 
          on u.user_id = c.user_id 
          $where
          order by $sortCol $sortDir 
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