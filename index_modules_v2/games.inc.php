<div align="center" class="narrow" style="text-align:center;">
  <br>
    <img src="<?=IMAGE_HOST_SITE_GFX?>/games_hdr.gif">
    <?
    $res = db_query("SELECT * FROM game_info WHERE is_live='1' ORDER BY RAND() LIMIT 1");
    if ( $row = db_fetch_object($res) )
    {
      if ( strlen($row->php_game_url) > 0 ) {
        $PLAY_PAGE = 'http://'.DOMAIN.$row->php_game_url;
      }
      else {
        $PLAY_PAGE = 'http://'.DOMAIN.'/games/play/'.str_replace("'", "-", str_replace(" ", "_", $row->title)).'.php';
      }
      ?><a href='<?=$PLAY_PAGE?>'><img src='<?=IMAGE_HOST?>/games/thumbnails/game_<?=$row->game_id?>_tn_med.gif' border='0' style="border:1px solid black;"></a><?
    }
    ?>
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" width="200">
      <?
      $i = 0;
      $res = db_query("SELECT * FROM highscore_top_100 WHERE game_id='".$row->game_id."' ORDER BY highscore DESC LIMIT 3");
      while($row = db_fetch_object($res) )
      {
        ++$i;
        ?><tr><td align="left"><?=$i?>. <?=username($row->username)?></td><td align="right"><?=number_format($row->highscore)?> pts.</td></tr><?
      }
      ?>
    </table>
</div>