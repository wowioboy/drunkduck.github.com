<?php
require_once('../../includes/db.class.php');

foreach ($_POST['filter'] as $column => $filter) {
  $orArray = array();
  foreach ($filter as $value) {
    $orArray[] = "`$column` = '$value'";
  }
  $or = '(' . implode(' or ', $orArray) . ')';
  $where[] = $or;
}
if (is_array($where)) {
  $where = ' and ' . implode(' and ', $where);
}

$query = "select c.comic_id as id, c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author
from comics c 
left join users u 
on u.user_id = c.user_id 
where c.total_pages > 0 
$where 
order by rand() 
limit 10";
$random = DB::getInstance()->fetchAll($query);

echo json_encode($random);
