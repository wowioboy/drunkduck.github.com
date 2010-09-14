<p><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_sub_need.gif" width="260" height="20" /></p>
<?
      $NEED = array();
      $res = db_query("SELECT comic_id FROM comics_in_need ORDER BY need DESC LIMIT 5");
      while ($row = db_fetch_object($res)) {
        $NEED[] = $row->comic_id;
      }
      db_free_result($res);
      
      $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", $NEED)."')");
      while ($row = db_fetch_object($res)) {
        $COMICS[$row->comic_id] = $row;
      }
      db_free_result($res);
      
      foreach($NEED as $inNeed) 
      {
        if (date("Ymd",$COMICS[$inNeed]->last_update)==YMD)
        {
          echo '<p><img src="'.IMAGE_HOST.'/site_gfx_new/genre_icons/'.$COMICS[$inNeed]->category.'.gif" alt="Genre: '.$COMIC_CATS[$COMICS[$inNeed]->category].'" width="12" height="12" /> <a href="/'.comicNameToFolder($COMICS[$inNeed]->comic_name).'/">'.$COMICS[$inNeed]->comic_name.' *</a></p>';
        }
        else 
        {
          echo '<p><img src="'.IMAGE_HOST.'/site_gfx_new/genre_icons/'.$COMICS[$inNeed]->category.'.gif" alt="Genre: '.$COMIC_CATS[$COMICS[$inNeed]->category].'" width="12" height="12" /> <a href="/'.comicNameToFolder($COMICS[$inNeed]->comic_name).'/">'.$COMICS[$inNeed]->comic_name.'</a></p>';
        }
      }
    ?>