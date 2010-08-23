<div id="needcomments">
  <img src="<?=IMAGE_HOST_SITE_GFX?>/sect_needcmnts_01.gif" width="150" height="22" />
  <?
  $NEED = array();
  $res = db_query("SELECT comic_id FROM comics_in_need ORDER BY need DESC");
  while ($row = db_fetch_object($res)) {
    $NEED[$row->comic_id] = $row->comic_id;
    if (count($NEED) == 5 ) break;
  }
  db_free_result($res);

  $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", $NEED)."')");
  while ($row = db_fetch_object($res)) {
    $COMICS[$row->comic_id] = $row;
  }
  db_free_result($res);

  foreach($NEED as $inNeed)
  {
    $extra = '';
    if (date("Ymd",$COMICS[$inNeed]->last_update)==YMD) {
      $extra = ' *';
    }
    ?><p><img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$COMICS[$inNeed]->category?>.gif" alt="Genre: <?=$COMIC_CATS[$COMICS[$inNeed]->category]?>" width="12" height="12" /> <a href="http://www.drunkduck.com/<?=comicNameToFolder($COMICS[$inNeed]->comic_name)?>"><?=$COMICS[$inNeed]->comic_name.$extra?></a></p><?
  }
  ?>
</div>