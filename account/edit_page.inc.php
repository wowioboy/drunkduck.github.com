<?
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

<SPAN CLASS='headline'>Editing Page: "<?=$PAGE_ROW->page_title?>"</SPAN>

<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Page Info:</DIV>
  
    <FORM ACTION='send_page_edit.php' METHOD='POST' ENCTYPE='multipart/form-data'>
    
    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>
    
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Page Title:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <INPUT TYPE='TEXT' NAME='pageTitle' VALUE='<?=htmlentities($PAGE_ROW->page_title, ENT_QUOTES)?>' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Authors Notes:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <TEXTAREA NAME='authorsNotes' STYLE='WIDTH:100%;HEIGHT:100;'><?=htmlentities($PAGE_ROW->comment, ENT_QUOTES)?></TEXTAREA>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Page:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <INPUT TYPE='FILE' NAME='comicPage' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    </TABLE>
    
    <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>
    <INPUT TYPE='HIDDEN' NAME='pid' VALUE='<?=$PID?>'>
    
    <DIV ALIGN='CENTER'>
      <INPUT TYPE='CHECKBOX' NAME='delete' VALUE='1'> Delete?<BR>
      <INPUT TYPE='SUBMIT' VALUE='Send these changes!'>
    </DIV>
    
    </FORM>
  
</DIV>

<?
  echo "<BR>";
  echo "<IMG SRC='http://".DOMAIN."/".$FOLDER_NAME."/pages/".md5($CID.$PAGE_ROW->page_id).'.'.$PAGE_ROW->file_ext."'>";
?>