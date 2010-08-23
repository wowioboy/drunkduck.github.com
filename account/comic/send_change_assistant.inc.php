<?
$CID = (int)$_POST['cid'];

  $res = db_query("SELECT * FROM comics WHERE user_id='".$USER->user_id."' AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);

  $assistant = db_escape_string(trim($_POST['assistant']));

  if ( strlen($assistant) == 0 )
  {
    db_query("UPDATE comics SET secondary_author=null WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
  else
  {
    $res = db_query("SELECT * FROM users WHERE username='".$assistant."'");
    if ( $assistantRow = db_fetch_object($res) ) {
      db_free_result($res);
      db_query("UPDATE comics SET secondary_author='".$assistantRow->user_id."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
    }
    db_free_result($res);
  }
  header("Location: http://".DOMAIN."/account/comic/?cid=".$CID);
?>