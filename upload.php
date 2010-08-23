<?php
include_once('includes/global.inc.php');
//include_once('includes/simpleimage.php');

if (!$CID = $_REQUEST['cid']) {
	return;
}

# GET THE COMIC OBJECT
$query = "select * from comics where comic_id = '$CID'";
$comic = db_fetch_object(db_query($query));

# GET FILE EXTENSION
$ext = getExt($_FILES['Filedata']['name']);

//$image = new SimpleImage();
//$image->load($_FILES['Filedata']['tmp_name']);
//if ($image->getWidth() > 1024) {
//	$image->resizeToWidth(1024);
//	$image->save($_FILES['Filedata']['tmp_name']);
//}

# MANIPULATE DATE
$lastUpdate = true;
if ($date = $_REQUEST['date']) {
	if ($date != date('m/d/Y')) {
		$lastUpdate = false;
	}
	$date = explode('/', $date);
	$date = mktime(0, 0, 0, $date[0], $date[1], $date[2]);
} else {
	$date = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
}

# INSERT PAGE INTO DB
$query = "select * from comic_pages where comic_id = '$CID' order by order_id desc limit 1";
$page = db_fetch_object(db_query($query));
$order_id = $page->order_id + 1;
$title = strip_tags(db_escape_string($_REQUEST['title']));
$description = strip_tags(db_escape_string($_REQUEST['notes']));
$query = "insert into comic_pages (comic_id, post_date, order_id, page_title, comment, page_score, file_ext, user_id) VALUES ('$CID', '$date', '$order_id', '$title', '$description', '0', '$ext', '{$comic->user_id}')";
db_query($query);

# MOVE COMIC PAGE FILE
$ID = db_get_insert_id();
$NEW_FILENAME = md5($CID.$ID). ".$ext";
$FOLDER = comicNameToFolder($comic->comic_name);
$path = 'comics/'.$FOLDER{0}.'/'.$FOLDER.'/pages/'.$NEW_FILENAME;
copy($_FILES['Filedata']['tmp_name'], $path);

# UPDATE PAGE TOTAL
$query = "select count(1) as total_pages from comic_pages where comic_id = '{$comic->comic_id}'";
$row = db_fetch_object(db_query($query));
$comic->total_pages = $row->total_pages;
$query = "update comics set total_pages = '{$comic->total_pages}'";
if ($lastUpdate) {
	$query .= ", last_update = '$date'";
}
$query .= " where comic_id = '{$comic->comic_id}'";
db_query($query);
echo '1';
?>
