<?
include('header_edit_comic.inc.php');

if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];
if ( !isset($_GET['pid'] ) ) return;
$PID = (int)$_GET['pid'];

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
?>

<DIV CLASS='container' ALIGN='LEFT'>
  <TABLE WIDTH='100%' BORDER='0' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD COLSPAN='6' ALIGN='CENTER' valign="bottom"><h3 align="left"><span style="float:left;"><a href="comic_pages_edit.php">Comic Pages</a>: <?=htmlentities($PAGE_ROW->page_title, ENT_QUOTES)?></span></h3></TD>
    </TR>
    <tr>
      <td id="main" align="center" valign="top" width="100%">
        <div class="container" align="left">
          <form action="send_page_edit.php" method="post" enctype="multipart/form-data">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td colspan="2" align="left" class="community_hdr">Page Info</td>
            </tr>
            <tr>
              <td width="20%" align="left" valign="top" class="community_thrd">
                <b>Page Title:</b><br>
                <span class="helpnote">The page title appears in the drop down menu for your comic. </span>
              </td>
              <td width="70%" align="left" class="community_thrd">
                <input name="pageTitle" value="<?=htmlentities($PAGE_ROW->page_title, ENT_QUOTES)?>" style="width: 100%;" type="text">
              </td>
            </tr>
            <tr>
              <td width="20%" align="left" valign="top" class="community_thrd">
                <b>Authors Notes:</b>
              </td>
              <td width="70%" align="left" class="community_thrd">
                <textarea name="authorsNotes" rows="5" style="width: 100%;"><?=htmlentities($PAGE_ROW->comment, ENT_QUOTES)?></textarea>
              </td>
            </tr>
            <tr>
              <td width="20%" align="left" valign="top" class="community_thrd">
                <b>Page Graphic:</b><br>
                <span class="helpnote">If you need to change this page, select your new image here.</span>
              </td>
              <td width="70%" align="left" class="community_thrd">
                <input name="comicPage" style="width: 100%;" type="file">
              </td>
            </tr>
            <tr>
              <td align="left">&nbsp;</td>
              <td align="left">
                <div align="right">
                  <span class="delete"><input name="delete" value="1" type="checkbox" style="margin:0; padding:0; vertical-align:middle;">Delete this page?</span>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input name="submit" type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/changes_send.gif" value="Send these changes!" style="vertical-align:middle;">
                </div>
              </td>
            </tr>
          </table>

          <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>
          <INPUT TYPE='HIDDEN' NAME='pid' VALUE='<?=$PID?>'>

          <div align="center"></div>

          </form>


          <strong>Current Graphic for this page: </strong></div>
          <br>
          <img src="http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/pages/<?=md5($CID.$PAGE_ROW->page_id)?>.<?=$PAGE_ROW->file_ext?>?r=<?=dice(1,10000)?>">
        </td>
      </TR>
      <TD colspan="6" ALIGN='LEFT' class="helpnote">
        <p>&nbsp;</p>
      </TD>
    </TR>
  </TABLE>
</DIV>
<?






include('footer_edit_comic.inc.php');
?>