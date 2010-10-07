<?php
require_once('../includes/db.class.php');

$comic = $_REQUEST['comic'];
$comic = DB::getInstance()->fetchRow("select description from comics where comic_name = '$comic'");
echo json_encode($comic);