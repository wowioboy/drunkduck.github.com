<div style="height:24px;background:url(<?=$IMAGE_FOLDER?>/sub_title_hdr.gif);color:#ffffff;font-weight:bold;" align="left">
  <div style="padding-left:5px;padding-top:4px;">Comics Recommended by <?=$viewRow->username?></div>
</div>

<div style="background:#ffffff;margin-bottom:5px;" align="left">
  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <?
      $RECOMMENDS = array();
      $res = db_query("SELECT comic_id FROM comic_favs WHERE user_id='".$viewRow->user_id."' AND recommend='1'");
      while( $row = db_fetch_object($res) ) {
        $RECOMMENDS[$row->comic_id] = $row;
      }
      db_free_result($res);

      $COMIC_ROWS = array();
      $res = db_query("SELECT comic_id, comic_name, total_pages, last_update FROM comics WHERE comic_id IN ('".implode("','", array_keys($RECOMMENDS))."')  ORDER BY RAND()");
      while($row = db_fetch_object($res) ) {
        $COMIC_ROWS[$row->comic_id] = $row;
      }
      db_free_result($res);

      if ( count($COMIC_ROWS) == 0 ) {
        ?><td align="left">None.</td><?
      }
      else
      {
        $ct = -1;
        foreach( $COMIC_ROWS as $row )
        {
          $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
          if ( ++$ct%3 == 0 ) {
            ?></tr><tr><?
          }
          ?>
            <td align="center" valign="top" width="33%">
              <a href='<?=$url?>'><img src="<?=thumb_processor($row)?>" border="0">
              <br>
              <?=$row->comic_name?></a>
              <br>
              <div style="font-size:9px;color:#a5a5a5;"><?=number_format($row->total_pages)?> pgs | Last: <?=((date("Ymd", $row->last_update)==YMD)?"Today":date("m/d/y", $row->last_update))?></div>
            </td>
          <?
        }
        db_free_result($res);
      }
      ?>
    </tr>
  </table>
  <?
  if ( $USER->user_id == $viewRow->user_id ) {
    ?><div align="right"><a href="http://<?=USER_DOMAIN?>/edit_recommended_comics.php">Edit your Recommends</a></div><?
  }
  ?>
</div>