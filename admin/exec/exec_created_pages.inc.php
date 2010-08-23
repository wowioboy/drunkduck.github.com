<DIV STYLE='WIDTH:810px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Created Pages</DIV>
<?


$BIGGEST_MONTH = 0;
$MONTH_STATS   = array();

$res = db_query("SELECT post_date FROM comic_pages");
while( $row = db_fetch_object($res) )
{
  $MONTH_STATS[ date("Y-m", $row->post_date) ]++;
}

foreach($MONTH_STATS as $key=>$value ) {
  if ( $value > $BIGGEST_MONTH ) {
    $BIGGEST_MONTH = $value;
  }
}


krsort($MONTH_STATS);

?><table border='0' cellpadding='0' cellspacing='0' width='800'><?


foreach($MONTH_STATS as $key=>$value )
{
  ?>
  <tr>
   <td align='left' width='100'><b><?=$key?></b></td>
   <td align='left' width='600'><div align='right' style='color:white;background:#0000ff;width:<?=( ($value/$BIGGEST_MONTH)*600 )?>px'>&nbsp;</div></td>
   <td align='left' width='100'><?=number_format($value)?></td>
  </tr>
  <?
}


?></table><?

?>