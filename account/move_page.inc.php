<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can adjust page orders.</SPAN>";
    return;
  }
  
  if ( !$_GET['cid'] ) return;
  $CID = (int)$_GET['cid'];
  if ( !$_GET['pid'] ) return;
  $PID = (int)$_GET['pid'];
  if ( ($_GET['dir'] != -1) && ($_GET['dir'] != 1) ) return;
  $DIR = $_GET['dir'];
  
  $res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);
  
  $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$CID."' AND page_id='".$PID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $PAGE_ROW = db_fetch_object($res);
  db_free_result($res);
  
  if ( $DIR < 0 )
  {
    // Move Down
    $res = db_query("UPDATE comic_pages SET order_id='".$PAGE_ROW->order_id."' WHERE comic_id='".$CID."' AND order_id='".($PAGE_ROW->order_id-1)."'");
    $res = db_query("UPDATE comic_pages SET order_id='".($PAGE_ROW->order_id-1)."' WHERE page_id='".$PAGE_ROW->page_id."'");
  }
  else 
  {
    // Move up
    $res = db_query("UPDATE comic_pages SET order_id='".$PAGE_ROW->order_id."' WHERE comic_id='".$CID."' AND order_id='".($PAGE_ROW->order_id+1)."'");
    $res = db_query("UPDATE comic_pages SET order_id='".($PAGE_ROW->order_id+1)."' WHERE page_id='".$PAGE_ROW->page_id."'");
  }
  
  $i=1;
  $res = db_query("SELECT page_id, order_id FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id ASC");
  while ( $row = db_fetch_object($res) )
  {
    if ( $row->order_id != $i ) {
      db_query("UPDATE comic_pages SET order_id='".$i."' WHERE page_id='".$row->page_id."'");
    }
    $i++;
  }
  
  header("Location: http://".DOMAIN."/account/edit_comic.php?cid=".$CID."&p=".$_GET['p']);
?>