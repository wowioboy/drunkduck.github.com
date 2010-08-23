<?
include('community_header.inc.php');

$CID = (int)$_POST['cat_id'];

if ( $CID )
{
  if ($USER->flags & FLAG_IS_ADMIN) {
    db_query("UPDATE community_categories SET category_desc='".db_escape_string($_POST['new_desc'])."' WHERE category_id='".$CID."'");
  }
}

echo 'Click <a href="index.php?'.passables_query_string().'">here</a> to return to the topic you were reading.';

?><meta http-equiv="refresh" content="2;url=index.php<?=passables_query_string()?>">