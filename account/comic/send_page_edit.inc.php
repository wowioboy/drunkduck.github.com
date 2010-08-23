<?php
require_once(WWW_ROOT . '/includes/simpleimage.php');
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics.</SPAN>";
    return;
  }

  $CID = (int)( ($_POST['cid'])?$_POST['cid']:$_GET['cid'] );
  if ( !$CID ) return;

  $PID = (int)( ($_POST['pid'])?$_POST['pid']:$_GET['pid'] );
  if ( !$PID ) return;

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

  if ( $_POST['delete'] || $_GET['delete'] )
  {
    unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/'.md5($CID.$PAGE_ROW->page_id).'.'.$PAGE_ROW->file_ext);
    if ( true  )
    {
      db_query("DELETE FROM page_comments WHERE page_id='".$PID."'");
      db_query("DELETE FROM comic_pages WHERE page_id='".$PID."'");

      $lastInLine = null;
      $i=1;
      $res = db_query("SELECT page_id, order_id, post_date FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id ASC");
      while ( $row = db_fetch_object($res) )
      {
        if ( ($row->post_date <= time()) && ($row->order_id < $PAGE_ROW->order_id) ) {
          $lastInLine = $row;
        }

        if ( $row->order_id != $i ) {
          db_query("UPDATE comic_pages SET order_id='".$i."' WHERE page_id='".$row->page_id."'");
        }
        $i++;
      }

      if ( !$lastInLine ) {
        $lastInLine = new stdClass();
        $lastInLine->post_date = time();
      }

      db_query("UPDATE comics SET last_update='".$lastInLine->post_date."' WHERE comic_id='".$CID."'");

      header("Location: http://".DOMAIN."/account/comic/manage_pages.php?cid=".$CID);
      return;
    }
  }
  else if ( $_POST['pageTitle'] )
  {
    if ( is_uploaded_file($_FILES['comicPage']['tmp_name']) )
    {
      $ext = getExt($_FILES['comicPage']['name']);

      if ( !in_array($ext, $ALLOWED_UPLOADS) ) {
        echo "<DIV ALIGN='CENTER' CLASS='microalert'>The file extension: ".$ext." is not supported.</DIV>";
        return false;
      }
      if ( ($_FILES['comicPage']['size']/1024) > $GLOBALS['max_filesize'] ) {
      echo "<DIV ALIGN='CENTER' CLASS='microalert'>File: "+$_FILES['comicPage']['name']+" exceeded our maximum filesize of ".number_format($_FILES['comicPage']['size']/1024, 1)."k</DIV>";
      return false;
    	}
      
    	if (isset($_FILES['comicPage'])) {
//	        $image = new SimpleImage();
//			$image->load($_FILES['comicPage']['tmp_name']);
//			if ($image->getWidth() > 1024) {
//				$image->resizeToWidth(1024);
//				$image->save($_FILES['comicPage']['tmp_name']);
//			}
	        copy($_FILES['comicPage']['tmp_name'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/'.md5($CID.$PAGE_ROW->page_id).'.'.$ext);
	        $PAGE_ROW->file_ext = $ext;
    	}
    }

    db_query("UPDATE comic_pages SET page_title='".strip_tags(db_escape_string($_POST['pageTitle']))."', comment='".strip_tags(db_escape_string($_POST['authorsNotes']))."', file_ext='".$PAGE_ROW->file_ext."', user_id='".$USER->user_id."' WHERE page_id='".$PAGE_ROW->page_id."'");
  }
  header("Location: http://".DOMAIN."/account/comic/edit_page.php?cid=".$CID."&pid=".$PID);
?>