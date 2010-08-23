<script language="JavaScript">
function showMoreMostReadStories()
{
  for(var i=6; i<11; i++) {
    $('story_'+i).style.display = '';
  }
}
</script>
<img src="<?=IMAGE_HOST_SITE_GFX?>/sect_stories1.gif" width="212" height="30" />
<div class="narrow">
  <table width="206" border="0" cellpadding="0" cellspacing="0" class="topstories">
    <tr>
      <td width="80" rowspan="10" align="center" valign="top" id='top_stories_prev'>
        <?
        $extra = '';
        if ( isset($GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]) ) {
          $extra = "AND search_style='".$GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]."' ";
        }

        if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) ) {
          $extra .= "AND (search_category='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."' OR search_category_2='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."')";
        }

        $res = db_query("SELECT comic_id, comic_name, last_update, category, rating_symbol, flags FROM comics WHERE comic_type='1' ".$extra." AND total_pages>0 AND delisted=0 ORDER BY seven_day_visits DESC LIMIT 10");
        db_data_seek($res, dice(1, 10)-1);
        $row = db_fetch_object($res);
        ?><img src="<?=thumb_processor($row)?>"><?
        ?>
      </td>
      <?
      db_data_seek($res);
      $ct = 0;
      //$res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_type='1' AND total_pages>0 AND delisted=0 ORDER BY visits DESC LIMIT 5");
      while ($row = db_fetch_object($res))
      {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';

        // If this isn't the first one add a table row ( Due to the rowspan )
        if ( ++$ct > 1 ) { ?><tr><? }

        $id = '';
        if ( $ct > 5 ) {
          $id = 'id="story_'.$ct.'" style="display:none;"';
        }
        ?><td <?=$id?> width="116" height="20" valign="middle" class="padding" onMouseOver="$('top_stories_prev').innerHTML='<?=str_replace("'", "\\'", "<img src='".thumb_processor($row)."'>")?>';">
            <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" />
            <a href="<?=$url?>"><?=$row->comic_name.((date("Ymd",$row->last_update)==YMD)?" *":"")?></a>
          </td>
        </tr><?
      }
    ?>
    <tr>
      <td height="20" colspan="2" align="right" valign="middle" id='top_stories_prev'>
        <a href="#" onClick="showMoreMostReadStories();this.style.display='none';return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_more.gif" width="56" height="14" border="0" /></a>
        <a href="http://<?=DOMAIN?>/search.php?browsetype=1&sortby=read"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_browse.gif" width="56" height="14" border="0" /></a>
      </td>
    </tr>
  </table>
</div>