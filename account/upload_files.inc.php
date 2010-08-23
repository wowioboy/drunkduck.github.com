<?
if ( !isset($_GET['cid']) ) return;

$CID = (int)$_GET['cid'];

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);
$FOLDER_NAME = str_replacE(' ', '_', $COMIC_ROW->comic_name);


if ( isset($_GET['deleteName']) )
{
  $del = stripslashes($_GET['deleteName']);
  $del = ltrim($del, '.');
  if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/'.$del) ) {
    unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/'.$del);
  }
  else if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/html/'.$del) ) {
    unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/html/'.$del);
  }

  header("Location: http://".DOMAIN."/account/upload_files.php?cid=".$CID);
}

if ( count($_FILES) && (count($_FILES)<=5) )
{
  for($i=1; $i<count($_FILES)+1; $i++)
  {
    $fName = 'file_'.$i;

    if ( is_uploaded_file($_FILES[$fName]['tmp_name']) )
    {
      $ext = getExt($_FILES[$fName]['name']);
      if ( !in_array($ext, $ALLOWED_UPLOADS) && !in_array($ext, $ALLOWED_HTML_UPLOADS) ) {
        echo "<DIV ALIGN='CENTER' CLASS='microalert'>The file extension: ".$ext." is not supported.</DIV>";
        return;
      }

      if ( in_array($ext, $ALLOWED_UPLOADS) || in_array($ext, $ALLOWED_GFX_FILES) ) {
        copy($_FILES[$fName]['tmp_name'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/'.$_FILES[$fName]['name']);
      }
      else if ( in_array($ext, $ALLOWED_HTML_UPLOADS) ) {
        copy($_FILES[$fName]['tmp_name'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/html/'.$_FILES[$fName]['name']);
      }
    }
  }

  header("Location: http://".DOMAIN."/account/upload_files.php?cid=".$CID);
}



?>
<script type="text/javascript">
var uploadCt  = 1;
var uploadMax = 5;
function addUpload()
{
  if ( uploadCt == uploadMax ) {
    alert("You've reached the maximum numbers of files you can add at once!");
    return;
  }
  uploadCt++;

  var bgColor = '';
  var padding = '';
  if ( uploadCt%2 == 0 ) {
    bgColor = '#ECECEC';
    padding = 'PADDING-LEFT:50px;';
  }

  var divRef = document.getElementById('formArea');

  divRef.innerHTML +=
  //"<HR />"+
  "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%' BGCOLOR='"+bgColor+"' STYLE='"+padding+"'>"+
    "<TR>"+
      "<TD ALIGN='LEFT' WIDTH='30%'>"+
        "<B>File:</B>"+
      "</TD>"+
      "<TD ALIGN='LEFT' WIDTH='70%'>"+
        "<INPUT TYPE='FILE' NAME='file_"+uploadCt+"' STYLE='WIDTH:100%;'>"+
      "</TD>"+
    "</TR>"+
  "</TABLE>";

  document.getElementById('uploadCount').value = uploadCt;
}
</script>

<SPAN CLASS='headline'>Add Files</SPAN>

<DIV STYLE='WIDTH:600px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Files: </DIV>
  <DIV ALIGN='CENTER' CLASS='microalert'>Allowed Types: <?=implode(", ", $ALLOWED_UPLOADS)?>, <?=implode(", ", $ALLOWED_HTML_UPLOADS)?></DIV>
    <FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST' ENCTYPE='multipart/form-data'>

    <DIV ID='formArea'>
    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Page:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <INPUT TYPE='FILE' NAME='file_1' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    </TABLE>
    </DIV>

    <INPUT ID='uploadCount' TYPE='HIDDEN' NAME='uploads' VALUE='1'>
    <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>

    <DIV ALIGN='CENTER'>
      <INPUT TYPE='SUBMIT' VALUE='Send these files!'>
    </DIV>

    </FORM>
  <A HREF='JavaScript:addUpload();'>Add Upload Form!</A>
</DIV>

<P>&nbsp;</P>

<DIV STYLE='WIDTH:600px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Current Files: </DIV>
<?
$PADDING = 20;
?>
  <DIV ALIGN='LEFT' STYLE='PADDING-LEFT:<?=$PADDING?>px;'>
    <B><U>http://www.drunkduck.com/<?=$FOLDER_NAME?>/gfx/</U></B>
  </DIV>

  <?
  $PADDING = 70;

  $FCOUNT = 0;
  $dp = opendir(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx');
  while ($file = readdir($dp) )
  {
    if ( $file != '.' && $file != '..' )
    {
      ?>
      <DIV ALIGN='LEFT' STYLE='PADDING-LEFT:<?=$PADDING?>px;'>
        <LI><A HREF='http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/gfx/<?=$file?>' TARGET='_BLANK'><?=$file?></A>
        <A HREF='http://<?=DOMAIN?>/account/upload_files.php?cid=<?=$CID?>&deleteName=<?=$file?>'><SPAN CLASS='microalert'>Delete?</SPAN></A>
        </LI>
      </DIV>
      <?
      $FCOUNT++;
    }
  }
  if ( $FCOUNT == 0 ) {
    ?>
    <DIV ALIGN='LEFT' STYLE='PADDING-LEFT:<?=$PADDING?>px;'>
      <I>No Graphic Files</I>
    </DIV>
    <?
  }

  $PADDING = 20;
  ?>
  <P>&nbsp;</P>
  <DIV ALIGN='LEFT' STYLE='PADDING-LEFT:<?=$PADDING?>px;'>
    <B><U>http://www.drunkduck.com/<?=$FOLDER_NAME?>/html/</U></B>
  </DIV>


  <?
  $PADDING = 70;

  $FCOUNT = 0;
  $dp = opendir(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/html');
  while ($file = readdir($dp) )
  {
    if ( $file != '.' && $file != '..' )
    {
      ?>
      <DIV ALIGN='LEFT' STYLE='PADDING-LEFT:<?=$PADDING?>px;'>
        <LI><A HREF='http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/html/<?=$file?>' TARGET='_BLANK'><?=$file?></A>
        <A HREF='http://<?=DOMAIN?>/account/upload_files.php?cid=<?=$CID?>&deleteName=<?=$file?>'><SPAN CLASS='microalert'>Delete?</SPAN></A>
        </LI>
      </DIV>
      <?
      $FCOUNT++;
    }
  }
  if ( $FCOUNT == 0 ) {
    ?>
    <DIV ALIGN='LEFT' STYLE='PADDING-LEFT:<?=$PADDING?>px;'>
      <I>No Html Files</I>
    </DIV>
    <?
  }

  $PADDING = 20;
  ?>
</DIV>