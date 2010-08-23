<div style="height:24px;background:url(<?=$IMAGE_FOLDER?>/sub_title_hdr.gif);color:#ffffff;font-weight:bold;" align="left">
  <div style="padding-left:5px;padding-top:4px;">Comics assisted by <?=$viewRow->username?></div>
</div>

<div style="background:#ffffff;margin-bottom:5px;" align="left">
  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <?
      $res = db_query("SELECT * FROM comics WHERE secondary_author='".$viewRow->user_id."'");

      if ( db_num_rows($res) == 0 ) {
        ?><td align="left">None.</td><?
      }

      $ct = -1;
      while($row = db_fetch_object($res))
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
      ?>
    </tr>
  </table>
</div>