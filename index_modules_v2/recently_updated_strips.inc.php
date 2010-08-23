<script language="JavaScript">
function showMoreStrips()
{
  if ( $('hidestrips').style.display != '' ) {
    $('hidestrips').style.display = '';
  }
  else {
    window.open("http://<?=DOMAIN?>/search.php","_top");
  }
}
</script>
<img src="<?=IMAGE_HOST_SITE_GFX?>/sect_strips2.gif" width="212" height="30" />
<div class="narrow">

  <?
  $extra = '';
        if ( isset($GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]) ) {
          $extra = "AND search_style='".$GLOBALS['SUBDOM_TO_STYLE'][SUBDOMAIN]."' ";
        }

        if ( isset($GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]) ) {
          $extra .= "AND (search_category='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."' OR search_category_2='".$GLOBALS['SUBDOM_TO_CAT'][SUBDOMAIN]."')";
        }

  $ct = 0;
  $res = db_query("SELECT comic_name, last_update, category, flags FROM comics WHERE comic_type='0' ".$extra." AND total_pages>0 AND delisted=0 ORDER BY last_update DESC LIMIT 25");
  while ($row = db_fetch_object($res))
  {
    if ( ++$ct == 11 )
    {
      ?>
      <div id="hidestrips" style="display:none;">
      <?
    }
    $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';

    ?><div style="padding:3px;"><img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" title="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.((date("Ymd",$row->last_update)==YMD)?" *":"")?></a></div><?
  }
  db_free_result($res);
  ?>
  </div>
  <div align="right">
    <a href="#" onClick="showMoreStrips();return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_more.gif" width="56" height="14" border="0" /></a>
    <a href="http://<?=DOMAIN?>/search.php"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_browse.gif" width="56" height="14" border="0" /></a>
  </div>
</div>