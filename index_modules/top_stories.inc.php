<table width="280" border="0" cellpadding="0" cellspacing="0" class="topstories" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_book.png);">
  <tr>
    <td colspan="2" valign="top"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_top5_stories.png" width="280" height="35" class="headerimg" /></td>
  </tr>
  <tr>
    <td width="120" rowspan="6" align="center" valign="middle" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);" id='top_stories_prev'><?
      $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_type='1' AND total_pages>0 AND delisted=0 ORDER BY seven_day_visits DESC LIMIT 5");
      $row = db_fetch_object($res);
      echo get_current_thumbnail($row->comic_id, $row->comic_name);
    ?></td>
      <?
      db_data_seek($res);
      $ct = 0;
      //$res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_type='1' AND total_pages>0 AND delisted=0 ORDER BY visits DESC LIMIT 5");
      while ($row = db_fetch_object($res)) 
      {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        
        // If this isn't the first one add a table row ( Due to the rowspan )
        if ( ++$ct > 1 ) { ?><tr><? }
        
        ?><td width="160" height="20" valign="middle" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);" onMouseOver="$('top_stories_prev').innerHTML='<?=str_replace("'", "\\'", get_current_thumbnail($row->comic_id, $row->comic_name))?>';">
            <img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" />
            <a href="<?=$url?>"><?=$row->comic_name.((date("Ymd",$row->last_update)==YMD)?" *":"")?></a>
          </td>
        </tr><?
      }
    ?>
    <tr>
      <td height="20" align="right" valign="middle" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);"><a href="http://<?=DOMAIN?>/search.php?browsetype=1&sortby=read">browse stories...</a></td>
    </tr>
</table>