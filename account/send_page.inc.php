<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics.</SPAN>";
    return;
  }

  if ( !$_POST['cid'] ) return;
  $CID = (int)$_POST['cid'];

  $res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);


  if ( $_POST['uploads'] > 10 ) {
    $_POST['uploads'] = 10;
  }
  $uploads = (int)$_POST['uploads'];

  $post_date = false;
  $redir = true;
  for ($i=1; $i<($uploads+1); $i++)
  {
    if ( is_uploaded_file($_FILES['file_'.$i]['tmp_name']) )
    {
      if ( $_POST['live_month_'.$i] && $_POST['live_day_'.$i] && $_POST['live_year_'.$i] ) {
        $post_date = mktime(0, 0, 0, $_POST['live_month_'.$i], $_POST['live_day_'.$i], $_POST['live_year_'.$i]);
      }
      else {
        $post_date = time();
      }

      if ( !acceptUpload('file_'.$i, $_POST['pageTitle_'.$i], $_POST['comicDescription_'.$i], $post_date) ) {
       $redir = false;
      }
    }
  }


  if ( $post_date )
  {
    if ( date("Ymd") == date("Ymd", $post_date) ) {
      db_query("UPDATE comics SET last_update='".time()."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
    }
  }

  $res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
  $row = db_fetch_object($res);
  db_free_result($res);
  $COMIC_ROW->total_pages = $row->total_pages;
  db_query("UPDATE comics SET total_pages='".$COMIC_ROW->total_pages."' WHERE comic_id='".$COMIC_ROW->comic_id."'");

  if ( $redir ) {
    header("Location: http://".DOMAIN."/account/edit_comic.php?cid=".$CID);
  }

  function acceptUpload($fileName, $title, $notes, $post_date)
  {
    global $ALLOWED_UPLOADS;
    global $COMIC_ROW;
    global $CID;
    global $USER;

    if ( ($_FILES[$fileName]['size']/1024) > $GLOBALS['max_filesize'] ) {
      echo "<DIV ALIGN='CENTER' CLASS='microalert'>File: "+$_FILES[$fileName]['name']+" exceeded our maximum filesize of ".number_format($_FILES[$fileName]['size']/1024, 1)."k</DIV>";
      return false;
    }

    $ext = getExt($_FILES[$fileName]['name']);

    if ( !in_array($ext, $ALLOWED_UPLOADS) ) {
      echo "<DIV ALIGN='CENTER' CLASS='microalert'>The file extension: ".$ext." is not supported.</DIV>";
      return false;
    }

    $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id DESC LIMIT 1");
    $row = db_fetch_object($res);
    db_free_result($res);
    $order_id = $row->order_id+1;

    db_query("INSERT INTO comic_pages (comic_id, post_date, order_id, page_title, comment, page_score, file_ext, user_id) VALUES ('".$CID."', '".$post_date."', '".$order_id."', '".strip_tags(db_escape_string($title))."', '".strip_tags(db_escape_string($notes))."', '0', '".$ext."', '".$USER->user_id."')");
    $ID           = db_get_insert_id();
    $NEW_FILENAME = md5($CID.$ID).'.'.$ext;

    $FOLDER = comicNameToFolder($COMIC_ROW->comic_name);
    copy($_FILES[$fileName]['tmp_name'], WWW_ROOT.'/comics/'.$FOLDER{0}.'/'.$FOLDER.'/pages/'.$NEW_FILENAME);

    return true;
  }
?>