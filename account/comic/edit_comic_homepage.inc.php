<?
include('header_edit_comic.inc.php');
?>

<script type="text/javascript">
  function updateCharCt(field, updateDiv, limit)
  {
    if ( field.value.length >= 255 ) {
      field.value = field.value.substring(0, 255);
      alert('You have reached the limit of the description size you are allowed.');
    }
    var div = document.getElementById(updateDiv);
    div.innerHTML = (limit-field.value.length)+" characters left.";
  }

  function make18() {
    ajaxCall('/xmlhttp/toggle_age.php?cid=7825&make18=1', updateInfo);
  }
  function makeUnder18() {
    ajaxCall('/xmlhttp/toggle_age.php?cid=7825', updateInfo);
  }
  function updateInfo(nfo) {
    document.getElementById('ageGroup').innerHTML = nfo;
  }
</script>

<?
if ( isset($_POST['homePageOn']) )
{
  if ( $_POST['homePageOn'] == 0 ) {
    $COMIC_ROW->flags &= ~FLAG_USE_HOMEPAGE;
    db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
  else {
    $COMIC_ROW->flags |= FLAG_USE_HOMEPAGE;
    db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
}


  ?>

<div class="pagecontent">

  <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>?cid=<?=$COMIC_ROW->comic_id?>" method="POST">
  <TABLE width="100%" BORDER='0' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD VALIGN='TOP'>

        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
          <tr>
            <td colspan="3" align='LEFT' valign="top">
              <h3>Comic Homepage:</h3>
            </td>
          </tr>
          <tr>
            <td colspan="2" align='LEFT' valign="top" class="community_thrd">
              <input name='homePageOn' type='RADIO' value='1' <?=( ($COMIC_ROW->flags & FLAG_USE_HOMEPAGE)?"CHECKED":"")?>>
              <strong>Homepage ON</strong><br>
              <span class="helpnote">Turn the homepage. When you turn on your hompage, you can enable all sorts of cool things, like your own blog and forums and easily share your favorite DrunkDuck comics with your readers. </span>
            </td>
            <td width="50%" align='LEFT' valign="top" class="community_thrd">
              <input type='RADIO' name='homePageOn' value='0' <?=( ($COMIC_ROW->flags & FLAG_USE_HOMEPAGE)?"":"CHECKED")?>>
              <strong>Homepage OFF </strong><br>
              <span class="helpnote">Turn off the homepage. When the homepage is off, users will get the most recently posted comic page when they click on your comic. </span>
            </td>
          </tr>
          <!--
          <tr>
            <td colspan="3" align='LEFT' valign="top" class="community_thrd"><h4>Homepage Content:</h4>
              <h4><span class="helpnote">Select which content modules to display on your homepage. Click the checkbox to enable or disable a module from displaying on your homepage. </span></h4>
            </td>
          </tr>
          <tr>
            <td align='center' valign="top" class="community_thrd">
              <? $img = homepage_image($COMIC_ROW->comic_name); ?>
              <input name="homepage_image" type="checkbox" value="1" <?=( ($img) ? "CHECKED" : "" )?>>
            </td>
            <td align='LEFT' valign="top" class="community_thrd">
              <strong>Homepage Image</strong><br>
              <span class="helpnote">A graphic that will appear on your homepage below the title but above the blog and other content.</span>
            </td>
            <td align='LEFT' valign="top" class="community_thrd">
              Image:
              <input name="homepage_image" type="file" style="width:0px;border:0px;height:20px;">
              <input name="submit" type="submit" style="border:0px;height:20px;" value="Send">
            </td>
          </tr>
          <tr>
            <td width='15%' align='center' valign="top" class="community_thrd">
              <input name="checkbox6" type="checkbox" value="checkbox" checked>
            </td>
            <td width="35%" align='LEFT' valign="top" class="community_thrd">
              <b>Gallery</b><br>
              <span class="helpnote">Display link to your image gallery.</span>
            </td>
            <td width="50%" align='LEFT' valign="top" class="community_thrd">
              <a href="comic_other_edit_page.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a>
            </td>
          </tr>
          <tr>
            <td width='15%' align='center' valign="top" class="community_thrd">
              <input name="checkbox5" type="checkbox" value="checkbox" checked>
            </td>
            <td width="35%" align='LEFT' valign="top" class="community_thrd">
              <b>Links to other pages</b><br>
              <span class="helpnote">Display a list of links to other pages you've created for your comic.</span>
            </td>
            <td width="50%" align='LEFT' valign="top" class="community_thrd">
              <p><input name="checkbox53" type="checkbox" value="checkbox" checked>Character Pages</p>
              <p><input name="checkbox54" type="checkbox" value="checkbox" checked>Location Pages</p>
              <p><input name="checkbox55" type="checkbox" value="checkbox" checked>Bio Pages</p>
              <p><a href="comic_other_edit_page.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a></p>
            </td>
          </tr>
          <tr>
            <td width='15%' align='center' valign="top" class="community_thrd">
              <input name="checkbox52" type="checkbox" value="checkbox" checked>
            </td>
            <td width="35%" align='LEFT' valign="top" class="community_thrd">
              <b>Comic Description</b><br>
              <span class="helpnote">Display your comic's description on the homepage. You can display the long or short comic description. </span>
            </td>
            <td width="50%" align='LEFT' valign="top" class="community_thrd">
              <p>
                <select name="select2">
                <option>Long</option>
                <option>Short</option>
                </select>
                Description
              </p>
              <p><a href="comic_other_edit_page.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a></p>
            </td>
          </tr>
          <tr>
            <td align='center' valign="top" class="community_thrd">
              <input name="checkbox4" type="checkbox" value="checkbox" checked>
            </td>
            <td align='LEFT' valign="top" class="community_thrd">
              <b>Recommended DrunkDuck Comics</b>
              <p><span class="helpnote">Display some or all of your favorite comics on your homepage. Share links with your favorite creators! </span></p>
            </td>
            <td align='LEFT' valign="top" class="community_thrd">
              <p>Display favorites as
                <select name="select3">
                  <option>Images only</option>
                  <option>Images and Text Links</option>
                  <option>Text Links only</option>
                </select>
              </p>
              <p><a href="comic_other_edit_page.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a> </p>
            </td>
          </tr>
          <tr>
            <td align='center' valign="top" class="community_thrd">
              <input name="checkbox3" type="checkbox" value="checkbox" checked>
            </td>
            <td align='LEFT' valign="top" class="community_thrd">
              <b>Comic Credits</b>
              <p><span class="helpnote">Display credits on the homepage.</span></p>
            </td>
            <td align='LEFT' valign="top" class="community_thrd"><a href="comic_other_edit_page.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a></td>
          </tr>
          -->
          <tr>
            <td colspan="3" align="left" valign="top">
              <div align="right">
                <input name="SUBMIT2" type='image' src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/hp_edit.gif" value='Update Comic!'>
              </div>
            </td>
          </tr>
        </table>
      </TD>
    </TR>
  </TABLE>
  <input type="hidden" name="cid" value="<?=$COMIC_ROW->comic_id?>">
  </form>

</div>
<?
include('footer_edit_comic.inc.php');
?>