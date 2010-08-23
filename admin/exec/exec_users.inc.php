<DIV STYLE='WIDTH:810px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Signups</DIV>
<?


$SIGNUPS = array();
$BIGGEST_DAY = 0;
$TOTAL_TOTAL = 0;
$res = db_query("SELECT signed_up FROM users");
while($row = db_fetch_object($res)) 
{
  $SIGNUPS[date("Ymd", $row->signed_up)]++;
  $TOTAL_TOTAL++;
}

krsort($SIGNUPS, SORT_NUMERIC);

foreach($SIGNUPS as $amt) {
  if ( $BIGGEST_DAY < $amt ) {
    $BIGGEST_DAY = $amt;
  }
}

$KEYS = array_keys($SIGNUPS);
$GROWTH = array();

for($i=0; $i<count($KEYS);$i++)
{
  $key = $KEYS[$i];
  
  if ( $KEYS[$i+1] ) {
    $GROWTH[$KEYS[$i]] = $SIGNUPS[$KEYS[$i]]/$SIGNUPS[$KEYS[$i+1]];
  }
  else {
    $GROWTH[$KEYS[$i]] = 100;
  }
}

echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='800'>";
foreach( $SIGNUPS as $ymd=>$amt )
{
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
         <TD ALIGN='LEFT' WIDTH='600'><DIV ALIGN='RIGHT' STYLE='color:white;background:#0000FF;width:".( ($SIGNUPS[$ymd]/$BIGGEST_DAY)*600 )."px'>&nbsp;$grew</DIV></TD>
         <TD ALIGN='LEFT' WIDTH='100'>".number_format($SIGNUPS[$ymd])."</TD>
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
       <TD ALIGN='LEFT' WIDTH='100'>".number_format($TOTAL_TOTAL/count($SIGNUPS))."</TD>
      </TR>";
echo "</TABLE>";














function hourToHour($hr24)
{
  if ( $hr24 == 0  ) return '12am';
  if ( $hr24 == 12 ) return '12pm';
  
  if ( $hr24 > 12 ) return ($hr24-12).'pm';
  if ( $hr24 ) return $hr24.'am';
}


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

SELECT * FROM unique_tracking ORDER BY year_date ASC, month_date ASC, day_date ASC;
|        33458 |         33630 |       37593 |      2006 |          2 |       20 |
|        37107 |         37300 |       41072 |      2006 |          2 |       21 |
|        37272 |         37389 |       41292 |      2006 |          2 |       22 |
|        37078 |         37318 |       41144 |      2006 |          2 |       23 |
|        43215 |         43320 |       47146 |      2006 |          2 |       24 |
|        46487 |         46602 |       49776 |      2006 |          2 |       25 |
|        41247 |         41413 |       44644 |      2006 |          2 |       26 |
*/
?>
</DIV>