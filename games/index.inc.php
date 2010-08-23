<div align="left" class="header_title">
  Games
</div>

<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<div class="gameContent">
  <table border='0' width='600' cellpadding='5' cellspacing='0'>
    <tr>
      <td align='center'>
	      <img src='<?=IMAGE_HOST?>/games/difficulty/gamelevel_easy.gif' align="middle">
	      <br>
	      <b>Easy</b>
      </td>
      <td align='center'>
    	  <img src='<?=IMAGE_HOST?>/games/difficulty/gamelevel_med.gif' align="middle">
    	  <br>
    	  <b>Medium</b>
      </td>
      <td align='center'>
    	  <img src='<?=IMAGE_HOST?>/games/difficulty/gamelevel_hard.gif' align="middle">
    	  <br>
    	  <b>Difficult</b>
      </td>
    </tr>
  </table>

<hr style="width:600px;">

<?
  /* --------------------------------------------------
      Show all games
  -------------------------------------------------- */
  $query = "SELECT * FROM game_info WHERE is_live='1' ORDER BY game_id DESC";
  $result = db_query( $query );

  while( $row = db_fetch_object($result) )
  {
    if ( strlen($row->php_game_url) > 0 ) {
      $PLAY_PAGE = 'http://'.DOMAIN.$row->php_game_url;
    }
    else {
      $PLAY_PAGE = 'http://'.DOMAIN.'/games/play/'.str_replace("'", "-", str_replace(" ", "_", $row->title)).'.php';
    }

    ?>
    <p>
      <table border='0' width='600' cellpadding='5' cellspacing='0'>
        <tr>
          <td align='center' width="125" valign="top">
        		<a href='<?=$PLAY_PAGE?>'><img src='<?=IMAGE_HOST?>/games/thumbnails/game_<?=$row->game_id?>_tn_med.gif' border='0' style="border:1px solid black;"></a>
        		<div>
        		<a href='<?=$PLAY_PAGE?>'><?=$row->title?></a>
        		</div>
          </td>
          <td align="left" valign="top">
            <img src='<?=IMAGE_HOST?>/games/thumbnails/game_<?=$row->game_id?>_tn_small.gif' border='0' align="left" style="margin:5px;">
            <?=nl2br( BBCode($row->description) )?>
          </td>
          <td align="center" valign="middle" width="50">
            <img src='<?=IMAGE_HOST?>/games/difficulty/gamelevel_<?=$row->difficulty?>.gif'>
            <p>&nbsp;</p>
            <hr>
            <a href="http://<?=DOMAIN?>/games/highscores.php?game_id=<?=$row->game_id?>">High Scores</a>
          </td>
        </tr>
      </table>
    </p>
    <hr style="width:600px;">
    <?
  }
?>

<p>&nbsp;</p>

  <div style="padding:5px;width:230px;">
    <a href="http://<?=DOMAIN?>/download_file.php?fid=3">
      <img src="<?=IMAGE_HOST?>/site_gfx_new_v2/DDGames.jpg" border="0" style="border:1px solid black;">
      <br>
      Click here to download the Vista Desktop Gadget!
    </a>
  </div>

<p>&nbsp;</p>

</div>