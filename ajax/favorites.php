<?php
require_once('../includes/db.class.php');

$userId = $_REQUEST['user_id'];
if ($sort = $_REQUEST['sort']) {
  if ($sort == 'alpha') {
    $order = "order by comic_name asc";
  } else {
    $order = "order by last_update desc";
  }
}
$query = "select c.comic_id, c.comic_name as title, from_unixtime(c.last_update) as updated_on 
          from comics c 
          left join comic_favs f 
          on f.comic_id = c.comic_id
          where f.user_id = '$userId'
          $order";
$results = DB::getInstance()->fetchAll($query);
foreach ($results as &$result) {
  $date = new DateTime($result['updated_on']);
  $now = new DateTime();
  if ($date->format('Y-m-d') == $now->format('Y-m-d')) {
      $result['updated_on'] = 'Today';
  } else {
    $result['updated_on'] = $date->format('M j Y');
  }
} 
echo json_encode($results);