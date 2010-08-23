<?
  $query = "SELECT * FROM game_info WHERE title='".db_escape_string($CONVERTED_GAME_TITLE)."' AND is_live='1'";
  $result = db_query( $query );

  if ( !$row = db_fetch_object($result) ) {
    die("<div align='center'>Invalid Game</div>");
  }


  if ( $_GET['challenge_id'] )
  {
    echo "<DIV ALIGN='CENTER'><IMG SRC='".IMAGE_HOST."/site_gfx/challenge_symbol.gif'></DIV>";
  }


  $PLAY_PAGE = 'http://'.DOMAIN.'/games/play/'.str_replace(" ", "_", $row->title).'.php?play=1'.( ($_GET['challenge_id'])?"&challenge_id=".$_GET['challenge_id']:"");
  ?>
  <link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
  <div align="left" class="header_title">
    <?=$TITLE?>
  </div>
<div class="gameContent">
      <table border='0' cellpadding='5' cellspacing='0' width="400">
        <tr>
          <td align='center' valign='top'>
            <a href="<?=$PLAY_PAGE?>"><IMG SRC='<?=IMAGE_HOST?>/games/thumbnails/game_<?=$row->game_id?>_tn_big.gif' BORDER='0'></A>
          </td>
        </tr>

        <tr>
          <td align='left' valign='top' width='60%' height='100%'>

            <p>&nbsp;</p>
            <hr />
            <p>&nbsp;</p>

            <div align="center">
              <a href="<?=$PLAY_PAGE?>">Play Game</a>
              |
              <a href="javascript:w.openWin('<?=$PLAY_PAGE?>&pup=1', new WindowFormat('game_launch', <?=$row->width?>, <?=$row->height?>, 'no', 'no', 'no', 'no', 'no'));">Pop-up Game</a>
              |
              <a href='http://<?=DOMAIN?>/games/highscores.php?game_id=<?=$row->game_id?>'>High Scores</a>
            </div>

            <p>&nbsp;</p>
            <hr />
            <p>&nbsp;</p>

          </td>
        </tr>

        <tr>
          <td align='left' valign='top' width='60%' height='100%'>
            <b>Description:</b>
            <br>
            <IMG SRC='<?=IMAGE_HOST?>/games/difficulty/gamelevel_<?=$row->difficulty?>.gif' align="left" style="margin:5px;">
            <SPAN CLASS='subText'><?=nl2br( BBCode($row->description) )?></SPAN>
          </td>
        </tr>

      </table>
</div>