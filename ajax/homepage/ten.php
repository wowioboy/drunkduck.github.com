<?php
require_once('../../includes/db.class.php');

$query = "select c.comic_id as id, c.comic_name as title, c.description, c.rating_symbol as rating, c.total_pages as pages, u.username as author 
from comics c 
inner join users u 
on u.user_id = c.user_id
order by visits desc 
limit 10";
$topTen = DB::getInstance()->fetchAll($query);

echo json_encode($topTen);
