<?
if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];

  $res = db_query("SELECT * FROM comics WHERE user_id='".$USER->user_id."' AND comic_id='".$CID."'");
  if ( db_num_rows($res) == 0 ) {
    db_free_result($res);
    return;
  }
  $COMIC_ROW = db_fetch_object($res);
  db_free_result($res);

if ( (strtolower($_POST['password']) == strtolower($USER->password)) )
{
  set_time_limit(0);
  ignore_user_abort(true);

  $FOLDER_NAME = str_replace(' ', '_', $COMIC_ROW->comic_name);

  if ( strlen($FOLDER_NAME) == 0 ) return;

  $COMIC_PATH = WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME;

  if ( file_exists($COMIC_PATH) )
  {
    $PATH = $COMIC_PATH.'/gfx';
    $dp = opendir($PATH);
    if ( !$dp ) return;
    while ($file = readdir($dp) ) {
      unlink($PATH.'/'.$file);
    }
    closedir($dp);
    rmdir($PATH);

    $PATH = $COMIC_PATH.'/pages';
    $dp = opendir($PATH);
    if ( !$dp ) return;
    while ($file = readdir($dp) ) {
      unlink($PATH.'/'.$file);
    }
    closedir($dp);
    rmdir($PATH);

    $PATH = $COMIC_PATH.'/html';
    $dp = opendir($PATH);
    if ( !$dp ) return;
    while ($file = readdir($dp) ) {
      unlink($PATH.'/'.$file);
    }
    closedir($dp);
    rmdir($PATH);

    $PATH = $COMIC_PATH;
    $dp = opendir($PATH);
    if ( !$dp ) return;
    while ($file = readdir($dp) ) {
      unlink($PATH.'/'.$file);
    }
    closedir($dp);
    rmdir($PATH);
  }

  db_query("DELETE FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM comics WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM tags_by_comic WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM tags_by_page WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM tags_used WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM comics_in_need WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM comic_html_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
  db_query("DELETE FROM comic_gallery_images WHERE comic_id='".$COMIC_ROW->comic_id."'");

  header("Location: http://".DOMAIN."/account/index.php");
}
?>


<script type="text/javascript">
function confirmDeletion()
{
  if ( !confirm('Are you SURE you want to delete this comic?') ) {
    return false;
  }
  if ( !confirm('Are you POSITIVE you want to delete this comic?') ) {
    return false;
  }
  if ( !confirm('This is your LAST CHANCE TO CHANGE YOUR MIND! Do you still want to delete this comic?') ) {
    return false;
  }
  return true;
}
</script>

<SPAN CLASS='headline'>DELETE THE COMIC: <?=$COMIC_ROW->comic_name?></SPAN>

<DIV STYLE='WIDTH:300px;HEIGHT:150px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Confirm by entering your password:</DIV>

<FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST' onSubmit="return confirmDeletion();">
<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%;'>
  <TR>
    <TD WIDTH='20%;'>
      Password:
    </TD>
    <TD WIDTH='80%;'>
      <INPUT TYPE='PASSWORD' NAME='password' STYLE='WIDTH:100%;'>
    </TD>
  </TR>
  <TR>
    <TD COLSPAN='2' ALIGN='CENTER'>
      <INPUT TYPE='SUBMIT' VALUE='DELETE!'>
    </TD>
  </TR>
</TABLE>
</FORM>

</DIV>