<table width="100%" height="20" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="00325D" id="searchbar">
  <tr>
    <form action="http://<?=DOMAIN?>/search.php" name="searchform" method="GET">
    <td align="center" valign="middle">
      <input name="searchTxt" type="text" value="Search DrunkDuck!" size="40" onclick="this.value='';"/>
      <input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/search_bar/btn_search.gif" width="53" height="16" align="absmiddle" border="0" style="padding:0px;" /> <input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/search_bar/btn_adv.gif" width="62" height="16" align="absmiddle" border="0" onClick="this.form.advanced.value=1;"  style="padding:0px;"/>
    </td>
    <td align="right" valign="middle"><a href="http://horror.drunkduck.com"><img src="<?=IMAGE_HOST?>/genre_nav_buttons/icon_5.gif" border="0"></a>
    <a href="http://manga.drunkduck.com"><img src="<?=IMAGE_HOST?>/genre_nav_buttons/icon_11.gif" border="0"></a></td>
    <input type="hidden" name="advanced" value="0">
    <?=( isset($SUBDOM_TO_CAT[SUBDOM]) ? '<input type="hidden" name="browsegenre[]" value="'.$SUBDOM_TO_CAT[SUBDOM].'">' : '' )?>
    </form>
  </tr>
</table>