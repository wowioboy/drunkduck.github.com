<?
$GAME_INFO = array();
$res = db_query("SELECT * FROM game_info");
while( $row = db_fetch_object($res) ) {
  $GAME_INFO[$row->game_id] = $row;
}
db_free_result($res);

$PLAYS[$date][$game_id] = $number;
$LAUNCHES[$date][$game_id] = $number;

$PLAYS    = array();
$LAUNCHES = array();
$DATES    = array();

if ( isset($_GET['game_id']) ) {
  $GAME_IDS = array( (int)$_GET['game_id'] );
}
else {
  $GAME_IDS = array_keys($GAME_INFO);
}


$res = db_query("SELECT * FROM game_launches WHERE game_id IN ('".implode("','", $GAME_IDS)."') ORDER BY date_year DESC, date_month DESC, date_day DESC");
while($row = db_fetch_object($res) )
{
  $date                               = $row->date_month .'.'. $row->date_day .'.'. $row->date_year;
  $LAUNCHES[ $row->game_id ][ $date ]    = $row->counter;
  $DATES[ $date ]                     = $row->date_month .'.'. $row->date_day .'.'. $row->date_year;
}
db_free_result($res);


$res = db_query("SELECT * FROM game_plays WHERE game_id IN ('".implode("','", $GAME_IDS)."') ORDER BY date_year DESC, date_month DESC, date_day DESC");
while($row = db_fetch_object($res) )
{
  $date                               = $row->date_month .'.'. $row->date_day .'.'. $row->date_year;
  $PLAYS[ $row->game_id ][ $date ] = $row->counter;
  $DATES[ $date ]                     = $row->date_month .'.'. $row->date_day .'.'. $row->date_year;
}
db_free_result($res);


$BIGGEST = 0;
foreach( $DATES as $date )
{
  foreach( $GAME_IDS as $game_id )
  {
    $TOTAL = $LAUNCHES[$game_id][$date] + $PLAYS[$game_id][$date];
    if ( $TOTAL > $BIGGEST ) {
      $BIGGEST = $TOTAL;
    }
  }
}



?>
<table border="0" cellpadding="0" cellspacing="0" width="900">
  <tr>
    <td align="center">
      <div style="background:#ff0000;width:200px;color:white;font-size:11px;">PLAYS</div>
      <div style="background:#0000ff;width:200px;color:white;font-size:11px;">LAUNCHES</div>
    </td>
  </tr>
<?
foreach( $DATES as $date )
{
  ?>
  <tr>
    <td align='center' bgcolor='#efefef'  style="border:1px solid white;">
      <?=$date?> <?= ( ($date == date("n.j.Y"))?"<font style='color:red;font-size:9px;'>Todays (Not completed)</font>":"") ?>
    </td>
  </tr>
  <tr>
    <td align='center' bgcolor='#efefef'  style="border:1px solid white;">
      <table border='0' cellpadding='0' cellspacing='0' width="100%">
      <?
      foreach( $GAME_IDS as $game_id )
      {
        $TOTAL_COUNT  = $LAUNCHES[$game_id][$date] + $PLAYS[$game_id][$date];
        $TOTAL_WIDTH  = ($TOTAL_COUNT / $BIGGEST) * 700;
        $LAUNCH_WIDTH = $LAUNCHES[$game_id][$date] / $TOTAL_COUNT;
        $PLAY_WIDTH   = $PLAYS[$game_id][$date] / $TOTAL_COUNT;

        ?>
        <tr>
          <td align='right' style="font-size:11px;padding-right:10px;"><b><?=$GAME_INFO[$game_id]->title?></b></td>
          <td align='left' width='710'><div align="right" style='background:#FF0000;width:<?=floor( $TOTAL_WIDTH*$PLAY_WIDTH )?>px;float:left;color:white;font-size:11px;font-weight:bold;padding-right:2px;'><?=number_format($PLAYS[$game_id][$date])?></div><div align="right" style='background:#0000FF;width:<?=floor( $TOTAL_WIDTH*$LAUNCH_WIDTH )?>px;float:left;color:white;font-size:11px;font-weight:bold;padding-right:2px;'><?=number_format($LAUNCHES[$game_id][$date])?></div></td>
        </tr>
        <?
      }
      ?>
      </table>
    </td>
  </tr><?
}
?></table>