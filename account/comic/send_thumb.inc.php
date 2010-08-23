<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics.</SPAN>";
    return;
  }

  if ( !$_POST['cid'] ) die("ERROR 1");
  $CID = (int)$_POST['cid'];

  $res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    die("ERROR 2");
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);

  if ( is_uploaded_file($_FILES['new_thumb']['tmp_name']) )
  {

    $_FILES['new_thumb']['name'] = md5(microtime().$_SERVER['REMOTE_ADDR']) . '.' . getFileExt($_FILES['new_thumb']['name']);

    cleanThumbs($COMIC_ROW->comic_id);

    $CACHE = WWW_ROOT.'/gfx/comic_thumbnails_cache/'.$_FILES['new_thumb']['name'];

    copy($_FILES['new_thumb']['tmp_name'], $CACHE);

    $num        = (string)$COMIC_ROW->comic_id{0};
    $COMIC_NAME = $COMIC_ROW->comic_name;

    $target = WWW_ROOT.'/comics/'.$COMIC_NAME{0}.'/'.str_replace(' ', '_', $COMIC_NAME).'/gfx/thumb.jpg';

    unlink( $target );

    thumb($CACHE, $target, 80, 100, true);
    echo $target . " = " . file_exists($target)."<BR>";

    unlink( $CACHE );
  }

  if ( $USER->user_id != 1 ) {
    header("Location: http://".DOMAIN."/account/comic/?cid=".$COMIC_ROW->comic_id);
  }
 ?>