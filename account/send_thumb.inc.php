<?
error_reporting(E_ALL | E_STRICT | E_ERROR);
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

  if ( is_uploaded_file($_FILES['new_thumb']['tmp_name']) )
  {
    $CACHE = WWW_ROOT.'/gfx/comic_thumbnails_cache/'.$_FILES['new_thumb']['name'];
    copy($_FILES['new_thumb']['tmp_name'], $CACHE);

    $num = (string)$COMIC_ROW->comic_id{0};
    thumb($CACHE, WWW_ROOT.'/gfx/comic_thumbnails/'.$num.'/comic_'.$COMIC_ROW->comic_id.'.jpg', 80, 100, true);
  }

  //header("Location: /account/edit_comic.php?cid=".$_POST['cid']);
?>