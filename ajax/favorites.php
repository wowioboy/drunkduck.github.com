<?php
require_once('../includes/db.class.php');

$search = $_REQUEST['search'];
$search = '%' . str_replace(' ', '%', $search) . '%';
$query = "seleccomic_name from comics where comic_name like '$search' limit 10";
$result = DB::getInstance()->fetchCol($query);
echo json_encode($result);