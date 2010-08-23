<?
include('header_edit_comic.inc.php');

ini_set("memory_limit", "128M");

$FOLDER_NAME = str_replace(' ', '_', $COMIC_ROW->comic_name);

if ( isset($_POST['template_choice']) ) {
  if ( $_POST['template_choice'] < 0 || $_POST['template_choice'] > 8 ) $_POST['template_choice'] = 0;
  $_POST['template_choice'] = (int)$_POST['template_choice'];
  db_query("UPDATE comics SET template='".$_POST['template_choice']."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  $COMIC_ROW->template = $_POST['template_choice'];
}

  ?>
  <FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST'>
  <table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
      <td colspan="2" align='LEFT' valign="top">
        <h3 align="left"><span style="float:left;"><a href="comic_design.php?cid=<?=$COMIC_ROW->comic_id?>">Comic Page Layout</a>: DD Templates for your Homepage:</span></h3>
      </td>
    </tr>
    <tr>
      <td colspan="2" align='LEFT' valign="top" class="community_hdr">Home Page Design </td>
    </tr>
    <tr>
      <td width="20%" rowspan="10" align='LEFT' valign="top" class="community_thrd">
        <div align="right">
          <strong>Home Page Template:</strong><br>
          <span class="helpnote">Click on an image to see a larger preview of the design.</span>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="radiobutton" <?=(($COMIC_ROW->template==0)?"CHECKED":"")?>>
        0. Default/Custom HTML<br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/default_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/default_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/default_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/default_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="3" <?=(($COMIC_ROW->template==3)?"CHECKED":"")?>>
        1. Sky Blue <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/sky_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/sky_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/sky_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/sky_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="1" <?=(($COMIC_ROW->template==1)?"CHECKED":"")?>>
        2. Mallard Blue <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mb_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mb_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mb_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mb_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="7" <?=(($COMIC_ROW->template==7)?"CHECKED":"")?>>
        3. Mallard Green <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mg_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mg_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mg_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mg_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="2" <?=(($COMIC_ROW->template==2)?"CHECKED":"")?>>
        4. Mallard Red <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mr_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mr_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mr_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/mr_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="8" <?=(($COMIC_ROW->template==8)?"CHECKED":"")?>>
        5. Skull <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/skull_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/skull_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/skull_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/skull_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="4" <?=(($COMIC_ROW->template==4)?"CHECKED":"")?>>
        6. Brute <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/brute_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/brute_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/brute_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/brute_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="5" <?=(($COMIC_ROW->template==5)?"CHECKED":"")?>>
        7. Black White <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/bw_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/bw_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/bw_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/bw_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='center' valign="top" bgcolor="#BBBBBB" class="community_thrd">
        <input name="template_choice" type="radio" value="6" <?=(($COMIC_ROW->template==6)?"CHECKED":"")?>>
        8. White Black <br>
        <div style="float:left;padding-left:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/wb_home_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/wb_home.gif" border="0"></a>
        </div>
        <div style="float:right;padding-right:30px;">
          <a href="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/wb_comic_lg.gif"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/template_previews/wb_comic.gif" border="0"></a>
        </div>
      </td>
    </tr>
    <tr>
      <td height="0" align='LEFT' valign="top" class="community_thrd">&nbsp;</td>
      <td align='LEFT' valign="top" class="community_thrd">
        <div align="right">
          <p><input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/design_accept.gif" width="100" height="24" border="0"></p>
          <p><span class="helpnote">Click this button to apply any changes you have made. </span></p>
        </div>
      </td>
    </tr>
	</table>
  <input type="hidden" name="cid" value="<?=$CID?>">
  </FORM>
  <?

include('footer_edit_comic.inc.php');
?>