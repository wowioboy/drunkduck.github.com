<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add chapters.</SPAN>";
    return;
  }

  if ( !$_GET['cid'] ) return;
  $CID = (int)$_GET['cid'];
  if ( !$_GET['pid'] ) return;
  $PID = (int)$_GET['pid'];


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

  if ( $_GET['chap'] == 1 ) {
    db_query("UPDATE comic_pages SET is_chapter='1' WHERE page_id='".$PID."'");
  }
  else {
    db_query("UPDATE comic_pages SET is_chapter='0' WHERE page_id='".$PID."'");
  }

  header("Location: http://".DOMAIN."/account/edit_comic.php?cid=".$CID."&p=".$_GET['p']);
?>