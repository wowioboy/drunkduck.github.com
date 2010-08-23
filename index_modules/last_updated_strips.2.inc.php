<script language="JavaScript">
function showMoreStrips()
{
  if ( $('hidestrips').style.display != '' )
  {
    $('hidestrips').style.display = '';
  }
  $('showstrips').innerHTML = "<a href=\"http://<?=DOMAIN?>/search.php?browsetype=0&amtPerPage=50\">show more...</a>";
}
</script>
<table width="280" border="0" cellpadding="0" cellspacing="0" class="topstrips" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_strip.png);">
  <tr>
    <td colspan="2" valign="top"><img src="<?=IMAGE_HOST?>/site_gfx_new/stripsUpdated.png" width="280" height="25" class="headerimg" /></td>
  </tr>
  <tr>
    <td colspan="2" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
      <?
      $ct = 0;
      $res = db_query("SELECT comic_name, last_update, category, flags FROM comics WHERE comic_type='0' AND total_pages>0 AND delisted=0 ORDER BY last_update DESC LIMIT 25");
      while ($row = db_fetch_object($res)) 
      {
        if ( ++$ct == 11 ) {
          ?><div id="hidestrips" style="display:none;"><?
        }
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        ?><div style="padding:3px;"><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.((date("Ymd",$row->last_update)==YMD)?" *":"")?></a></div><?
      }
      db_free_result($res);
      ?>
      </div>
      <div id="showstrips" style="padding:3px;">
        <a href="#" onClick="showMoreStrips();return false;">show more...</a>
      </div>
    </td>
  </tr>
</table>