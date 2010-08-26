<?php 
require_once('../includes/db.class.php');

$like = new Like();
switch ($_REQUEST['action']) {
	case 'add':
		$like->add($_POST['page']);
		break;
	case 'getweek':
		echo json_encode($like->getWeek());
		break;
	case 'getbypage':
		echo $like->getByPage($_REQUEST['page']);
		break;
	default:
}

class Like
{
	public function add($page)
	{
		$ip = $_SERVER['REMOTE_ADDR'];		
		if (isset($page) && isset($ip)) {
			return DB::getInstance()->query("insert into page_likes (page_id, ip_address) values ('$page', '$ip')");
		}
	}
	
	public function getWeek()
	{
		$date = new DateTime();
		$offset = $date->format('N') - 1;
		$date->modify("-$offset day");
		$startDate = $date->format('Y-m-d') . ' 00:00:00';
		$date->modify('+6 day');
		$endDate = $date->format('Y-m-d') . ' 23:59:59';
		$query = "select comic_name as comic, count(1) as likes
				  from page_likes l 
				  left join comic_pages p
				  on l.page_id = p.page_id 
				  left join comics c 
				  on p.comic_id = c.comic_id 
				  group by c.comic_id 
				  order by likes desc";
		return DB::getInstance()->fetchAll($query);
	}
	
	public function getByPage($page)
	{
		if (isset($page)) {
			return DB::getInstance()->fetchOne("select count(1) from page_likes where page_id = '$page'");
		}
	}
}