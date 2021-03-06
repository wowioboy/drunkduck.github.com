<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/scriptaculous/effects.js"></SCRIPT>
<script type="text/javascript">
var divNow = null;
function displayDiv(name)
{
  var divNew = $(name);
  if ( divNew == divNow ) {
    new Effect.BlindUp(divNow);
    divNow = null;
    return;
  }
  if ( divNow ) {
    new Effect.BlindUp(divNow);
  }
  divNow = divNew;
  new Effect.BlindDown(divNew);
}
</script>
<DIV STYLE='WIDTH:810px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Pageviews</DIV>
  <DIV ALIGN='CENTER'><i>(click any date for hourly summary)</i></DIV>
<?
$DATES         = array();
$GRAND_TOTALS  = array();
$BIGGEST_HOURS = array();
$BIGGEST_DAY   = 0;
$TOTAL_TOTAL   = 0;

$DISTINCT_RES = db_query("SELECT DISTINCT(ymd_date) FROM global_pageviews ORDER BY ymd_date DESC");
while( $YMD_ROW = db_fetch_object($DISTINCT_RES) ) 
{
  $DATES[$YMD_ROW->ymd_date]        = array();
  $GRAND_TOTALS[$YMD_ROW->ymd_date] = 0;
  
  for ($i=23; $i>=0; $i--) {
    $DATES[$YMD_ROW->ymd_date][$i] = 0;
  }
  
  $res = db_query("SELECT * FROM global_pageviews WHERE ymd_date='".$YMD_ROW->ymd_date."' ORDER BY hour DESC");
  while( $row = db_fetch_object($res) ) 
  {
    $amt = ($row->counter*$row->multiplier);
    $DATES[$YMD_ROW->ymd_date][$row->hour] = $amt;
    $GRAND_TOTALS[$YMD_ROW->ymd_date]     += $amt;
    $TOTAL_TOTAL                          += $amt;
    
    if ( $BIGGEST_HOURS[$YMD_ROW->ymd_date] < $amt ) {
      $BIGGEST_HOURS[$YMD_ROW->ymd_date] = $amt;
    }
  }
  
  if ( $BIGGEST_DAY < $GRAND_TOTALS[$YMD_ROW->ymd_date] ) {
    $BIGGEST_DAY = $GRAND_TOTALS[$YMD_ROW->ymd_date];
  }
}


$KEYS = array_keys($GRAND_TOTALS);
$GROWTH = array();

for($i=0; $i<count($KEYS);$i++)
{
  $key = $KEYS[$i];
  
  if ( $KEYS[$i+1] ) {
    $GROWTH[$KEYS[$i]] = $GRAND_TOTALS[$KEYS[$i]]/$GRAND_TOTALS[$KEYS[$i+1]];
  }
  else {
    $GROWTH[$KEYS[$i]] = 100;
  }
  
}

echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='800'>";
foreach( $DATES as $ymd=>$hourArray )
{
  $grew = round($GROWTH[$ymd], 2)*100;
  if ( $grew < 100 ) {
    $grew = '-'.(100-$grew).'%';
  }
  else if ( $grew > 100 ) {
    $grew = "+".($grew-100)."%";
  }
  if ( $grew == (100*100) ) {
    $grew = '';
  }
  
  list($yr,$mo,$dy) = sscanf($ymd, "%4d%2d%2d");
  echo "<TR onClick=\"displayDiv('div_".$ymd."');\">
         <TD ALIGN='LEFT' WIDTH='100'><B>$mo.$dy.$yr</B></TD>
         <TD ALIGN='LEFT' WIDTH='600'><DIV ALIGN='RIGHT' STYLE='color:white;background:#0000FF;width:".( ($GRAND_TOTALS[$ymd]/$BIGGEST_DAY)*600 )."px'>$grew</DIV></TD>
         <TD ALIGN='LEFT' WIDTH='100'>".number_format($GRAND_TOTALS[$ymd])."</TD>
        </TR>";
  
  echo "<TR>
          <TD ALIGN='CENTER' COLSPAN='3' BGCOLOR='#efefef'>";
  echo      "<DIV ID='div_".$ymd."' STYLE='display:none;'>
              <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='800'>";
  foreach( $hourArray as $hour=>$counter )
  {
    //list($yr,$dy,$mo) = sscanf($ymd, "%4d%2d%2d");
          echo "<TR>
                 <TD ALIGN='LEFT' WIDTH='100'>".hourToHour($hour)."</TD>
                 <TD ALIGN='LEFT' WIDTH='600'><DIV STYLE='background:#FF0000;width:".( ($counter/$BIGGEST_HOURS[$ymd])*600 )."px;'>&nbsp;</DIV></TD>
                 <TD ALIGN='LEFT' WIDTH='100'>".number_format($counter)."</TD>
                </TR>";
  }
      echo   "</TABLE>
            </DIV>";
    echo "</TD>
        </TR>";
}

echo "<TR>
       <TD ALIGN='LEFT' WIDTH='100'>&nbsp;</TD>
       <TD ALIGN='RIGHT' WIDTH='600'><B>Total</B>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
       <TD ALIGN='LEFT' WIDTH='100'>".number_format($TOTAL_TOTAL)."</TD>
      </TR>";
echo "<TR>
       <TD ALIGN='LEFT' WIDTH='100'>&nbsp;</TD>
       <TD ALIGN='RIGHT' WIDTH='600'><B>Average/Day</B>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
       <TD ALIGN='LEFT' WIDTH='100'>".number_format($TOTAL_TOTAL/count($DATES))."</TD>
      </TR>";

echo "</TABLE>";














function hourToHour($hr24)
{
  if ( $hr24 == 0  ) return "12am";
  if ( $hr24 == 12 ) return "12pm";
  
  if ( $hr24 > 12 ) return ($hr24-12).'pm';
  if ( $hr24 ) return $hr24.'am';
}

function getAvgDropExtremes($arr)
{
  $HIGHEST = 0;
  $LOWEST  = 0;
  $TOTAL   = 0;
  
  foreach($arr as $number) {
    if ( $number > $HIGHEST ) {
      $HIGHEST = $number;
    }
    else  if ( $number < $LOWEST ) {
      $LOWEST = $number;
    }
    
    $TOTAL += $number;
  }
  
  $TOTAL -= $LOWEST;
  $TOTAL -= $HIGHEST;
  
  return $TOTAL/(count($arr)-2);
}


/*

SELECT * FROM unique_tracking ORDER BY year_date ASC, month_date ASC, day_date ASC;
|        33458 |         33630 |       37593 |      2006 |          2 |       20 |
|        37107 |         37300 |       41072 |      2006 |          2 |       21 |
|        37272 |         37389 |       41292 |      2006 |          2 |       22 |
|        37078 |         37318 |       41144 |      2006 |          2 |       23 |
|        43215 |         43320 |       47146 |      2006 |          2 |       24 |
|        46487 |         46602 |       49776 |      2006 |          2 |       25 |
|        41247 |         41413 |       44644 |      2006 |          2 |       26 |





mysql> SELECT COUNT(*) FROM users;
+----------+
| COUNT(*) |
+----------+
|     2180 |
+----------+





mysql> SELECT COUNT(*) FROM comics;
+----------+
| COUNT(*) |
+----------+
|     1325 |
+----------+
*/
?>
</DIV>