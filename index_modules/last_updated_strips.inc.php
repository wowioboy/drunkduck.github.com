<table width="280" border="0" cellpadding="0" cellspacing="0" class="topstrips" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_strip.png);">
  <tr>
    <td colspan="2" valign="top"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_stripsUpdated.png" width="280" height="25" class="headerimg" /></td>
  </tr>
  <tr>
    <td colspan="2" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
      <?
      $res = db_query("SELECT comic_name, last_update, category, flags FROM comics WHERE comic_type='0' AND total_pages>0 AND delisted=0 ORDER BY last_update DESC LIMIT 5");
      while ($row = db_fetch_object($res)) {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        ?><p><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.((date("Ymd",$row->last_update)==YMD)?" *":"")?></a></p><?
      }
      db_free_result($res);
      ?>
      
    </td>
  </tr>
</table>