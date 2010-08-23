<?
  if ( !($USER->flags & FLAG_VERIFIED) )
  {
    echo "<SPAN CLASS='alert'>You must verify your account before you can add comics.</SPAN>";
    return;
  }

  if ( !$_POST['cid'] ) die("ERROR 1");;
  $CID = (int)$_POST['cid'];

  $res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    die("ERROR 2");
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);

  if ( is_uploaded_file($_FILES['new_title_image']['tmp_name']) )
  {
    $ext = getExt($_FILES['new_title_image']['name']);

    if ( !in_array($ext, $ALLOWED_UPLOADS) )
    {
      echo "<DIV ALIGN='CENTER' CLASS='microalert'>The file extension: ".$ext." is not supported.</DIV>";
      return;
    }
    else
    {
      $FOLDER = comicNameToFolder($COMIC_ROW->comic_name);
      foreach($ALLOWED_UPLOADS as $ext) {
        @unlink(WWW_ROOT.'/comics/'.$FOLDER{0}.'/'.$FOLDER.'/gfx/comic_title.'.$ext);
      }
      copy($_FILES['new_title_image']['tmp_name'], WWW_ROOT.'/comics/'.$FOLDER{0}.'/'.$FOLDER.'/gfx/comic_title.'.getFileExt($_FILES['new_title_image']['name']));
    }
  }

  header("Location: http://".DOMAIN."/account/comic/?cid=".$COMIC_ROW->comic_id);
?>