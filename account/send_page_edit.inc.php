<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics.</SPAN>";
    return;
  }

  if ( !$_POST['cid'] ) return;
  $CID = (int)$_POST['cid'];
  if ( !$_POST['pid'] ) return;
  $PID = (int)$_POST['pid'];

  $res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);

  $FOLDER_NAME = str_replace(" ", "_", $COMIC_ROW->comic_name);

  $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$CID."' AND page_id='".$PID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $PAGE_ROW = db_fetch_object($res);
  db_free_result($res);

  if ( $_POST['delete'] )
  {
    if ( unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/'.md5($CID.$PAGE_ROW->page_id).'.'.$PAGE_ROW->file_ext) )
    {
      db_query("DELETE FROM page_comments WHERE page_id='".$PID."'");
      db_query("DELETE FROM comic_pages WHERE page_id='".$PID."'");

      $i=1;
      $res = db_query("SELECT page_id, order_id FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id ASC");
      while ( $row = db_fetch_object($res) )
      {
        if ( $row->order_id != $i ) {
          db_query("UPDATE comic_pages SET order_id='".$i."' WHERE page_id='".$row->page_id."'");
        }
        $i++;
      }

      header("Location: http://".DOMAIN."/account/edit_comic.php?cid=".$CID);
      return;
    }
  }
  else
  {
    if ( is_uploaded_file($_FILES['comicPage']['tmp_name']) )
    {
      $ext = getExt($_FILES['comicPage']['name']);

      if ( !in_array($ext, $ALLOWED_UPLOADS) ) {
        echo "<DIV ALIGN='CENTER' CLASS='microalert'>The file extension: ".$ext." is not supported.</DIV>";
        return false;
      }

      if ( unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/'.md5($CID.$PAGE_ROW->page_id).'.'.$PAGE_ROW->file_ext) ) {
        copy($_FILES['comicPage']['tmp_name'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/'.md5($CID.$PAGE_ROW->page_id).'.'.$ext);
        $PAGE_ROW->file_ext = $ext;
      }
    }

    db_query("UPDATE comic_pages SET page_title='".strip_tags(db_escape_string($_POST['pageTitle']))."', comment='".strip_tags(db_escape_string($_POST['authorsNotes']))."', file_ext='".$PAGE_ROW->file_ext."', user_id='".$USER->user_id."' WHERE page_id='".$PAGE_ROW->page_id."'");
  }
  header("Location: http://".DOMAIN."/account/edit_page.php?cid=".$CID."&pid=".$PID);
?>