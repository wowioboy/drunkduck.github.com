<?php
require_once('../includes/db.class.php');

$search = $_REQUEST['search'];
$search = '%' . str_replace(' ', '%', $search) . '%';
$query = "select * from comic_name where comic_name from comics where comic_name like '$search' limit 10";
$result = DB::getInstance()->fetchCol($query);
echo json_encode($result);