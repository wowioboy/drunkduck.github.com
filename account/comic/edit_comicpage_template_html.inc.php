<?
include('header_edit_comic.inc.php');

ini_set("memory_limit", "128M");

$FOLDER_NAME = str_replace(' ', '_', $COMIC_ROW->comic_name);

if ( isset($_GET['homepage']) && $_GET['ck'] == md5('DD'.$USER->password) )
{
  if ( $_GET['homepage'] == 0 ) {
    $COMIC_ROW->flags &= ~FLAG_USE_HOMEPAGE;
    db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
  else {
    $COMIC_ROW->flags |= FLAG_USE_HOMEPAGE;
    db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
}

if ( isset($_GET['reset']) && $_GET['ck'] == md5('DD'.$USER->password) )
{
  unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');
  var_dump( copy( WWW_ROOT.'/comics/resource_files/templates/'.$COMIC_ROW->template.'/template.dd', WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd') );
  //header("Location: http://".DOMAIN."/account/comic/edit_comicpage_template_html.php?cid=".$CID);
}

// process upload now?
if ( isset($_POST['comicTemplateCode']) )
{
  if ( strlen($_POST['comicTemplateCode']) > 500000 )
  {
    echo "<div align='center'>Comic Page Template Code too long.</div>";
  }
  else
  {
    $_POST['comicTemplateCode'] = stripslashes($_POST['comicTemplateCode']);
    write_file($_POST['comicTemplateCode'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');
    // header("Location: http://".DOMAIN."/account/comic/edit_homepage_template_html.php?cid=".$CID);
  }
}

if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd') ) {
  $PAGE_TEMPLATE = implode('', file(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd'));
}
else {
  $PAGE_TEMPLATE = implode('', file(WWW_ROOT.'/comics/resource_files/default_template.new.dd'));
}





?>
    <FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST'>
    <TABLE width="100%" BORDER='0' CELLPADDING='0' CELLSPACING='0'>
      <TR>
        <TD VALIGN='TOP'>
          <table border='0' cellpadding='0' cellspacing='0' width='100%'>
            <tr>
              <td colspan="3" align='LEFT' valign="top"><h3 align="left"><span style="float:left;"><a href="comic_design.php?cid=<?=$COMIC_ROW->comic_id?>">Comic Page Layout</a>: Raw HTML:</span></h3></td>
              </tr>
            <tr>
              <td colspan="3" align='LEFT' valign="top" class="community_hdr"><div align="center">Homepage  </div></td>
              </tr>
            <tr>
              <td width="20%" height="0" align='LEFT' valign="top" class="community_thrd"><div align="right"><strong>Code: </strong></div></td>
              <td height="0" colspan="2" align='LEFT' valign="bottom" class="community_thrd">
                <div align="center">
                  <textarea name="comicTemplateCode" rows="30" style="width:100%;" WRAP='OFF'><?=htmlentities($PAGE_TEMPLATE, ENT_QUOTES)?></textarea>
                  <DIV ALIGN='CENTER'>
                    <A HREF='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&reset=0&ck=<?=md5('DD'.$USER->password)?>' onClick="return confirm('Are you SURE you want to reset all of your code?');">Reset</A>
                  </DIV>
                </div>
                </td>
              </tr>
            <tr>
              <td height="0" align='LEFT' valign="top" class="community_thrd">&nbsp;</td>
              <td height="0" align='LEFT' valign="top" class="community_thrd">
                <!--
                <p><a href="#"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_preview.gif" width="100" height="24" border="0"></a></p>
                <p class="helpnote">Click this button to preview your changes in a new window. </p>
                -->
              </td>
              <td align='LEFT' valign="top" class="community_thrd"><div align="right">
                <p><input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_accept.gif" width="100" height="24" border="0"></p>
                <p><span class="helpnote">Click this button to apply any changes you have made. </span></p>
              </div></td>
            </tr>
        	</table>
        </TD>
      </TR>
    </TABLE>
    <input type="hidden" name="cid" value="<?=$CID?>">
    </FORM>
<?

include('footer_edit_comic.inc.php');
?>