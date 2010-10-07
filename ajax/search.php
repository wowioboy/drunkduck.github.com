<?php
require_once('../includes/db.class.php');

$search = $_REQUEST['search'];
$search = '%' . str_replace(' ', '%', $search) . '%';
$query = "select comic_id as id, comic_name as title 
          from comics 
          where comic_name like '$search' 
          limit 10";
$result = DB::getInstance()->fetchAll($query);
echo json_encode($result);