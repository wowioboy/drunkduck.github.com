<link href="http://<?=DOMAIN?>/games/games.css" rel="stylesheet" type="text/css" />
<?

if ( !isset($_GET['game_id']) ) {
  echo("<DIV ALIGN='CENTER'>INVALID REQUEST</DIV>");
  return;
}

$game_id = (int)$_GET['game_id'];


$res = db_query("SELECT * FROM game_info WHERE game_id='".$game_id."'");
if ( !$GAME_INFO = db_fetch_object($res) )
{
  echo("<DIV ALIGN='CENTER'>INVALID REQUEST</DIV>");
  return;
}

$PLAY_PAGE = 'http://'.DOMAIN.'/games/play/'.str_replace(" ", "_", $GAME_INFO->title).'.php';

  ?>
<div align="left" class="header_title">
  HIGH SCORES: <?=$GAME_INFO->title?> Top 100
</div>
<div class="gameContent">

  <div align="center"><a href="<?=$PLAY_PAGE?>"><img src="<?=IMAGE_HOST?>/games/thumbnails/game_<?=$GAME_INFO->game_id?>_tn_big.gif" border="0"></a></div><?

if ( $USER )
{
  $res = db_query("SELECT * FROM user_highscores WHERE game_id='".$game_id."' AND username='".$USER->username."'");
  if ( $row = db_fetch_object($res) )
  {
    ?>
      <p>
      <table border='0' cellpadding='0' cellspacing='5' width='450'>
        <tr>
          <td align='center'>
            <i><b>Your Highest Score:</b> <?=number_format($row->highscore)?></i>
          </td>
        </tr>
      </table>
      </p>
    <?
  }
}

?>


<table border='0' cellpadding='0' cellspacing='5' width='450'>
  <tr>
    <td align='center' width='50'>
      <b>Rank</b>
    </td>
    <td align='center' width='150'>
      <b>Username</b>
    </td>
    <td align='center' width='150'>
      <b>Score</b>
    </td>
    <td align='center' width='100'>
      <b>Date</b>
    </td>
  </tr>

  <tr>
    <td colspan="4" align="center">
      <hr >
    </td>
  </tr>

<?

$res = db_query("SELECT * FROM highscore_top_100 WHERE game_id='".$game_id."' ORDER BY highscore DESC");
$i   = 1;
while ( $row = db_fetch_object($res) )
{
  ?>
  <tr <?=( ($USER->username == $row->username)?"BGCOLOR='#ffffff'":"" )?>>
    <td align='center' width='50'>
      <font style='font-size:11px;'><?=$i?></font>
    </td>
    <td align='center' width='150'>
      <font style='font-size:11px;'><?=username($row->username)?></font>
    </td>
    <td align='center' width='150'>
      <font style='font-size:11px;'><?=number_format($row->highscore)?></font>
    </td>
    <td align='right' width='150'>
      <font style='font-size:11px;'><?=date("n.d.Y g:ia", $row->unix_time)?></font>
    </td>
  </tr>
  <?
  ++$i;
}
 ?>
  <tr>
    <td colspan="4" align="center">
      <hr>
    </td>
  </tr>
</table>

<p>&nbsp;</p>
  <div align="center"><i>High Scores are cleared every Monday.</i></div>
<p>&nbsp;</p>

</div>