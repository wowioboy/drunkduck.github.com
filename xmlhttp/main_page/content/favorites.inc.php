favorites[1]
<?
  $OBJ = get_ajax_settings( $USER );

  if ( ($EXTRA != 'week') && ($EXTRA != 'last30') && ($EXTRA != 'all') ) {
    $EXTRA = $OBJ->favorites['range'];
  }

  switch($EXTRA)
  {
    case 'week':
      $TLIMIT_STAMP = time() - (86400 * 7);
      $OBJ->favorites['range'] = 'week';
    break;

    case 'last30':
      $TLIMIT_STAMP = time() - (86400 * 30);
      $OBJ->favorites['range'] = 'last30';
    break;

    case 'all':
    default:
      $TLIMIT_STAMP = 0;
      $OBJ->favorites['range'] = 'all';
    break;
  }

  if ( $USER ) {
    save_ajax_settings( $USER, $OBJ );
  }

?>
<div style="background-color:#828282;height:20px;" align="center">
  <div style="height:2px;"></div>
  <table border="0" cellpadding="0" cellspacing="0" width="90%" height="18">
    <tr>
      <td align="center" width="22%" style="color:#c9c9c9;font-size:11px;font-weight:bold;">
        updated:
      </td>
      <td align="center" width="26%" style="color:#000000;font-size:11px;font-weight:bold;<?=( ($OBJ->favorites['range']=='week') ? 'background-color:#ffffff;' : '' )?>">
        <div class="fake_link" onClick="expandFavorites();expandFavorites('week');">this week</div>
      </td>
      <td align="center" width="26%" style="color:#000000;font-size:11px;font-weight:bold;<?=( ($OBJ->favorites['range']=='last30') ? 'background-color:#ffffff;' : '' )?>">
        <div class="fake_link" onClick="expandFavorites();expandFavorites('last30');">last 30 days</div>
      </td>
      <td align="center" width="26%" style="color:#000000;font-size:11px;font-weight:bold;<?=( ($OBJ->favorites['range']=='all') ? 'background-color:#ffffff;' : '' )?>">
        <div class="fake_link" onClick="expandFavorites();expandFavorites('all');">view all</div>
      </td>
    </tr>
  </table>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="12" align="center" class="favorite_mini_text">
      g
    </td>
    <td width="12" align="center" class="favorite_mini_text">
      r
    </td>
    <td width="160" align="left" class="favorite_mini_text">
      &nbsp;&nbsp; name
    </td>
    <td width="30" align="center" class="favorite_mini_text">
      pages
    </td>
    <td width="86" align="center" class="favorite_mini_text">
      update
    </td>
  </tr>
  <?
    $ctr = 0;
    $COMIC_IDS = array();
    $res = db_query("SELECT comic_id FROM comic_favs WHERE user_id='".$USER->user_id."'");
    while($row = db_fetch_object($res)) {
      $COMIC_IDS[$row->comic_id] = $row;
    }
    db_free_result($res);

    if ( $USER->flags&FLAG_FAVS_BY_DATE ) {
      $res = db_query("SELECT comic_id, category, comic_name, last_update, rating_symbol, total_pages, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') AND last_update>='".$TLIMIT_STAMP."' ORDER BY last_update DESC");
    }
    else {
      $res = db_query("SELECT comic_id, category, comic_name, last_update, rating_symbol, total_pages, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') AND last_update>='".$TLIMIT_STAMP."' ORDER BY comic_name ASC");
    }

    $lastCat = "";
    $ROWS = array();
    $ct = 0;
    while ($row = db_fetch_object($res))
    {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        $bg  = 'odd';
        if ( ++$ct%2 == 0 ) {
          $bg = 'even';
        }
        $indicator = '';
        if  ( date("Ymd",$row->last_update) == YMD ) {
          $indicator = ' *';
        }

      ?>
      <tr>
        <td width="12" align="center" class="favorite_mini_text">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12">
        </td>
        <td width="12" align="center" class="favorite_mini_text">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$row->rating_symbol?>_sm.gif" width="12" height="12">
        </td>
        <td width="160" align="left" class="favorite_mini_text">
          <div align="left" style="width:160px;height:15px;overflow:hidden;">
            &nbsp;&nbsp; <a href="http://<?=DOMAIN?>/<?=comicNameToFolder($row->comic_name)?>/"><?=$row->comic_name?></a>
          </div>
        </td>
        <td width="30" align="center" class="favorite_mini_text">
          <?=number_format($row->total_pages)?>
        </td>
        <td width="86" align="center" class="favorite_mini_text">
          <?
          if ( date("Ymd", $row->last_update) == date("Ymd") ) {
            ?><font style="color:#ff0000;">TODAY</font><?
          }
          else if ( date("Ymd", $row->last_update-86400) == date("Ymd", time()-86400) ) {
            ?><font style="color:#e69f2c;">YESTERDAY</font><?
          }
          else {
            echo date("m/d/Y", $row->last_update);
          }
          ?>
        </td>
      </tr>
      <?
    }

    if ( count($COMIC_IDS) )
    {
      ?>
      <tr>
        <td colspan="5" align="center">
          <a href="http://<?=DOMAIN?>/account/manage_favs.php">Edit Favorites Preferences</a>
        </td>
      </tr>
      <?
    }

    ?>
</table>