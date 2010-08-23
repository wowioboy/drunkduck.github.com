<div id='feedback'>
</div>
<table width="578" border="0" cellpadding="0" cellspacing="0" class="topfaves">
  <tr>
    <td bgcolor="#005bab"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_Top_10.png" alt="" width="578" height="35" /></td>
  </tr>
  <tr>
    <td align="center">
    <?
    /*
      <script language="JavaScript">
      function mmove(e)
      {
        var txt;
        for(var x in event.srcElement ) {
          txt += ", "+(x);
        }        
        var move = (event.offsetX - event.srcElement.scrollLeft) - 195;
        if ( move < 0 ) move = 0;
        $('feedback').innerHTML = move;
        event.srcElement.scrollLeft = move;
        //alert( (event.offsetX / event.srcElement.scrollWidth) * event.srcElement.scrollWidth );
        //event.srcElement.scrollLeft = (event.offsetX / event.srcElement.scrollWidth) * event.srcElement.scrollWidth;
        //alert( event.srcElement.scrollLeft );
        //alert( event.srcElement.scrollWidth );
        //alert(txt);
        //alert(event.offsetX);
      }
      </script>*/
      ?>
      <div style="width:578px;height:190px;overflow:auto;">
        <table width="1200" border="0" cellpadding="0" cellspacing="0" class="topfaves">
    <?
      $COMIC_IDS = array();
      $res = db_query("SELECT comic_id FROM comic_favs_tally ORDER BY tally DESC LIMIT 10");
      while($row = db_fetch_object($res)) {
        $COMIC_IDS[] = $row->comic_id;
      }
      db_free_result($res);
      
      $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", $COMIC_IDS)."')");
      while ($row = db_fetch_object($res))
      {
        $COMIC_DATA[$row->comic_id] = $row;
        $COMIC_DATA[$row->comic_id]->url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
      }
      db_free_result($res);
      
      ?>
      <tr><?
      foreach($COMIC_IDS as $id)
      {
        $row = $COMIC_DATA[$id];
        ?>
        <td width="10%" align="center" valign="bottom" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
          <p><a href='<?=$row->url?>'><?=get_current_thumbnail($row->comic_id, $row->comic_name)?></a></p>
        </td>
        <?
      }
      ?>
      </tr>
      <tr><?
      foreach($COMIC_IDS as $id)
      {
        $row = $COMIC_DATA[$id];
        if (date("Ymd",$row->last_update)==YMD)
        {
          ?>
          <td width="10%" align="center" valign="top" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
            <p><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>"  title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href='<?=$row->url?>'><?=$row->comic_name?> *</a></p>
          </td>
          <?
        }
        else 
        {
          ?>
          <td width="10%" align="center" valign="top" class="padding" style="background-image: url(<?=IMAGE_HOST?>/site_gfx_new/bg_grad.png);">
            <p><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href='<?=$row->url?>'><?=$row->comic_name?></a></p>
          </td>
          <?
        }
      }
      ?>
      </tr>
      </table>
      </div>
      </td>
  </tr>
</table>