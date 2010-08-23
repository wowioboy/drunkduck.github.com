<DIV STYLE='WIDTH:810px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Uniques (Filtered)</DIV>
<?

/*
+---------------+--------+------+-----+---------+-------+
| Field         | Type   | Null | Key | Default | Extra |
+---------------+--------+------+-----+---------+-------+
| year_counter  | int(4) |      |     | 0       |       |
| month_counter | int(2) |      |     | 0       |       |
| day_counter   | int(2) |      |     | 0       |       |
| year_date     | int(4) |      | PRI | 0       |       |
| month_date    | int(2) |      | PRI | 0       |       |
| day_date      | int(2) |      | PRI | 0       |       |
+---------------+--------+------+-----+---------+-------+

SELECT * FROM unique_tracking_filtered  ORDER BY year_date ASC, month_date ASC, day_date ASC;
|        33458 |         33630 |       37593 |      2006 |          2 |       20 |
|        37107 |         37300 |       41072 |      2006 |          2 |       21 |
|        37272 |         37389 |       41292 |      2006 |          2 |       22 |
|        37078 |         37318 |       41144 |      2006 |          2 |       23 |
|        43215 |         43320 |       47146 |      2006 |          2 |       24 |
|        46487 |         46602 |       49776 |      2006 |          2 |       25 |
|        41247 |         41413 |       44644 |      2006 |          2 |       26 |
*/

$BIGGEST_DAY = 0;
$TOTAL_TOTAL = 0;
$UNIQUES     = array();
$MONTHS        = array();

$res = db_query("SELECT * FROM unique_tracking_filtered  ORDER BY year_date DESC, month_date DESC, day_date DESC");
while($row = db_fetch_object($res))
{
  $MONTHS[(int)$row->year_date.'-'.(int)$row->month_date] += $row->day_counter;

  $YMD = '';
  $YMD .= $row->year_date;
  $YMD .= (($row->month_date>9)?$row->month_date:'0'.$row->month_date);
  $YMD .= (($row->day_date>9)?$row->day_date:'0'.$row->day_date);

  $UNIQUES[$YMD] = $row->day_counter;
  $TOTAL_TOTAL += $row->day_counter;

  if ( $BIGGEST_DAY < $row->day_counter ) {
    $BIGGEST_DAY = $row->day_counter;
  }
}

krsort($UNIQUES, SORT_NUMERIC);

$KEYS = array_keys($UNIQUES);
$GROWTH = array();

for($i=0; $i<count($KEYS);$i++)
{
  $key = $KEYS[$i];

  if ( $KEYS[$i+1] ) {
    $GROWTH[$KEYS[$i]] = $UNIQUES[$KEYS[$i]]/$UNIQUES[$KEYS[$i+1]];
  }
  else {
    $GROWTH[$KEYS[$i]] = 100;
  }
}

$monthNow = -1;

echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='800'>";
foreach( $UNIQUES as $ymd=>$amt )
{
  list($y, $m, $d) = sscanf($ymd, '%4d%2d$2d');

  if ( $monthNow == -1 ) {
    $monthNow = $m;
  }

  if( $monthNow != $m )
  {
    echo "<TR>
           <TD ALIGN='LEFT' WIDTH='100'>&nbsp;</TD>
           <TD ALIGN='RIGHT' WIDTH='600'><B>Month ".$m." Total</B>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
           <TD ALIGN='LEFT' WIDTH='100'>".number_format($MONTHS[$y.'-'.$m])."</TD>
          </TR>";


    $monthNow = $m;
  }

  $grew = round($GROWTH[$ymd], 2)*100;
  if ( $grew == (100*100) ) {
    $grew = '';
  }
  else if ( $grew < 100 ) {
    $grew = '-'.(100-$grew).'%';
  }
  else if ( $grew > 100 ) {
    $grew = "+".($grew-100)."%";
  }
  else {
    $grew = '';
  }

  list($yr,$mo,$dy) = sscanf($ymd, "%4d%2d%2d");
  echo "<TR>
         <TD ALIGN='LEFT' WIDTH='100'><B>$mo.$dy.$yr</B></TD>
         <TD ALIGN='LEFT' WIDTH='600'><DIV ALIGN='RIGHT' STYLE='color:white;background:#0000FF;width:".( ($UNIQUES[$ymd]/$BIGGEST_DAY)*600 )."px'>&nbsp;$grew</DIV></TD>
         <TD ALIGN='LEFT' WIDTH='100'>".number_format($UNIQUES[$ymd])."</TD>
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
       <TD ALIGN='LEFT' WIDTH='100'>".number_format($TOTAL_TOTAL/count($UNIQUES))."</TD>
      </TR>";
echo "</TABLE>";











function hourToHour($hr24)
{
  if ( $hr24 == 0  ) return '12am';
  if ( $hr24 == 12 ) return '12pm';

  if ( $hr24 > 12 ) return ($hr24-12).'pm';
  if ( $hr24 ) return $hr24.'am';
}



?>
</DIV>