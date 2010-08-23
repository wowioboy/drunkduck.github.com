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
$FOLDER_NAME = str_replace(' ', '_', $COMIC_ROW->comic_name);


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

  header("Location: http://".DOMAIN."/account/comic/upload_files.php?cid=".$CID);
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

  header("Location: http://".DOMAIN."/account/comic/upload_files.php?cid=".$CID);
}


include('header_edit_comic.inc.php');

if ( isset($_POST['galleryImages']) )
{
  db_query("DELETE FROM comic_gallery_images WHERE comic_id='".$CID."'");
  foreach( $_POST['galleryImages'] as $file )
  {
    db_query("INSERT INTO comic_gallery_images (comic_id, image_path) VALUES ('".$CID."', '".db_escape_string($file)."')");
  }
}

$GALLERY_IMAGES = array();
$res = db_query("SELECT * FROM comic_gallery_images WHERE comic_id='".$CID."'");
while( $row = db_fetch_object($res) )
{
  $GALLERY_IMAGES[$row->image_path] = $row->image_path;
}

if ( $_GET['addFile'] )
{
  ?>
  <DIV CLASS='container' ALIGN='LEFT'>
    <FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST' ENCTYPE='multipart/form-data'>

    <DIV ID='formArea'>
    <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%'>
    <TR>
      <TD colspan="2" ALIGN='LEFT'><h3><SPAN CLASS='headline'><a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>">File Manager</a>: Add Files:</SPAN> </h3></TD>
      </TR>
    <TR>
      <TD colspan="2" ALIGN='LEFT' class="community_hdr"><span class="microalert">Allowed Types: gif, jpg, jpeg, png, swf, html, css, txt</span></TD>
    </TR>
    <TR>
      <TD WIDTH='20%' ALIGN='LEFT' class="community_thrd">
        <B>File:</B>      </TD>
      <TD WIDTH='45%' ALIGN='LEFT' class="community_thrd">
        <INPUT TYPE='FILE' NAME='file_1' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    </TABLE>
    </DIV>

    <INPUT ID='uploadCount' TYPE='HIDDEN' NAME='uploads' VALUE='1'>
    <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>

    <DIV ALIGN='CENTER'>
      <DIV ALIGN='CENTER'>
          <INPUT name="IMAGE" TYPE='IMAGE' VALUE='Send these Pages!' src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/files_send.gif">
      </DIV>
    </DIV>

    </FORM>
  </DIV>
  <?
}
else
{
  ?>

  <style type="text/css">
  <!--
  .style2 {font-size: 10px}
  -->
  </style>
  <div class="pagecontent">
    <form action="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>" method="POST">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan='6' align='left' valign="bottom"><h3>File Manager: </h3></td>
        </tr>
        <tr>
          <TD COLSPAN='6' ALIGN='CENTER' valign="bottom"><h4 align="left"><span style="float:left;">Graphics File Uploaded:</span><span style="float:right;"><a href="<?=$_SERVER['PHP_SELF']?>?addFile=1&cid=<?=$CID?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/files_add.gif" width="100" height="24" border="0" align="baseline"></a></span></h4></TD>
        </tr>
        <tr>
          <td align="center" valign="bottom" class="community_hdr">File Type</td>
          <!--
          <td align="center" valign="bottom" class="community_hdr">
            Gallery Image
          </td>
          -->
          <td valign="bottom" class="community_hdr">
            File name
          </td>
          <td valign="bottom" class="community_hdr"><div align="center">Uploaded</div></td>
          <td valign="bottom" class="community_hdr"><div align="center">File Size</div></td>
          <td align="center" valign="bottom" class="community_hdr">Delete?</td>
        </tr>
        <?
        $FCOUNT = 0;
        $FOLDER = WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx';
        $dp = opendir($FOLDER);
        while ($file = readdir($dp) )
        {
          if ( $file != '.' && $file != '..' )
          {
            ?>
            <tr>
              <td align="center" class="community_thrd"><?=strtoupper(getFileExt($file))?></td>
              <!--
              <td align="center" class="community_thrd">
                <input type="checkbox" name="galleryImages[]" value="<?=htmlentities($file, ENT_QUOTES)?>" <?=( isset($GALLERY_IMAGES[$file]) ? "CHECKED" : "" )?>>
              </td>
              -->
              <td class="community_thrd">
                <A HREF='http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/gfx/<?=$file?>' TARGET='_BLANK'><?=$file?></A>
                <br>
                <span class="style2">http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/gfx/<?=$file?></span>
              </td>
              <td class="community_thrd"><div align="center"><?=date("m/d/y", filectime($FOLDER.'/'.$file))?></div></td>
              <td class="community_thrd"><div align="center"><?=round(filesize($FOLDER.'/'.$file)/1024, 2)?>k</div></td>
              <td align="center" class="community_thrd"><A HREF='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&deleteName=<?=$file?>'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_del.gif" width="15" height="16" border="0"></a></td>
            </tr>
            <?
            $FCOUNT++;
          }
        }
        ?>
      </table>
      <div align="center">
        <!-- <input type="submit" value="Save Gallery Images"> -->
      </div>

    </form>





    <p>&nbsp;</p>









    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <TD COLSPAN='6' ALIGN='CENTER' valign="bottom"><h4 align="left"><span style="float:left;">HTML and Text Files Uploaded:</span></h4></TD>
      </tr>
      <tr>
        <td align="center" valign="bottom" class="community_hdr">File Type</td>
        <!--
        <td align="center" valign="bottom" class="community_hdr">
          Gallery Image
        </td>
        -->
        <td valign="bottom" class="community_hdr">
          File name
        </td>
        <td valign="bottom" class="community_hdr"><div align="center">Uploaded</div></td>
        <td valign="bottom" class="community_hdr"><div align="center">File Size</div></td>
        <td align="center" valign="bottom" class="community_hdr">Delete?</td>
      </tr>

      <?
      $FCOUNT = 0;
      $FOLDER = WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/html';
      $dp = opendir($FOLDER);
      while ($file = readdir($dp) )
      {
        if ( $file != '.' && $file != '..' )
        {
          ?>
          <tr>
            <td align="center" class="community_thrd"><?=strtoupper(getFileExt($file))?></td>
            <td align="center" class="community_thrd">

            </td>
            <td class="community_thrd">
              <A HREF='http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/html/<?=$file?>' TARGET='_BLANK'><?=$file?></A>
              <br>
              <span class="style2">http://<?=DOMAIN?>/<?=$FOLDER_NAME?>/html/<?=$file?></span>
            </td>
            <td class="community_thrd"><div align="center"><?=date("m/d/y", filectime($FOLDER.'/'.$file))?></div></td>
            <td class="community_thrd"><div align="center"><?=round(filesize($FOLDER.'/'.$file)/1024, 2)?>k</div></td>
            <td align="center" class="community_thrd"><A HREF='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&deleteName=<?=rawurlencode($file)?>'><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_del.gif" width="15" height="16" border="0"></a></td>
          </tr>
          <?
          $FCOUNT++;
        }
      }
      ?>
    </table>

  </div>

  <?
}



include('footer_edit_comic.inc.php');
?>