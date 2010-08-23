<div align="left" style="margin-bottom:5px;">
  <div style="font-size:16px;font-weight:bold;">
    High Scores
  </div>

  <?
  $SCORES = array();
  $res = db_query("SELECT * FROM user_highscores WHERE username='".db_escape_string($viewRow->username)."'");
  while($row = db_fetch_object($res)) {
    $SCORES[$row->game_id] = $row->highscore;
  }
  db_free_result($res);


  if ( count($SCORES) == 0 ) {
    ?>None.<?
  }
  else
  {
    $GAMES = array();
    $res = db_query("SELECT php_game_url, game_id, title FROM game_info WHERE game_id IN ('".implode("','", array_keys($SCORES))."')");
    while($row = db_fetch_object($res)) {
      $GAMES[$row->game_id] = $row;
    }
    db_free_result($res);


    foreach( $GAMES as $id=>$gameRow )
    {
      if ( strlen($gameRow->php_game_url) > 0 ) {
        $PLAY_PAGE = 'http://'.DOMAIN.$gameRow->php_game_url;
      }
      else {
        $PLAY_PAGE = 'http://'.DOMAIN.'/games/play/'.str_replace("'", "-", str_replace(" ", "_", $gameRow->title)).'.php';
      }
      ?>
      <div style="float:left;width:49%;height:120px;margin-bottom:5px;" align="center">
        <a href="<?=$PLAY_PAGE?>"><img src="<?=IMAGE_HOST?>/games/thumbnails/game_<?=$id?>_tn_med.gif" height="85" width="125"></a>
        <br>
        <div style="font-size:18px;font-weight:bold;"><?=number_format($SCORES[$id])?></div>
      </div>
      <?
    }
    db_free_result($res);

    if ( count($GAMES)%2 != 0 )
    {
      ?>
      <div style="float:left;width:49%;height:120px;margin-bottom:5px;" align="center">
      </div>
      <?
    }
  }

  ?>

</div>