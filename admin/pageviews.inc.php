<SPAN CLASS='headline'>Pageviews</SPAN>

<DIV STYLE='WIDTH:800px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Breakdown:</DIV>

  <TABLE BORDER='0' WIDTH='100%' CELLPADDING='0' CELLSPACING='0'>
  <?
  
  $res = db_query("SELECT DISTINCT(ymd_date) FROM page_statistics");
  while($row = db_fetch_object($res)) {
    $DATES[] = $row->ymd_date;
  }
  db_free_result($res);
  arsort($DATES);
  
  $BIGGEST = 0;
  foreach($DATES as $ymd)
  {
    $res = db_query("SELECT * FROM page_statistics WHERE ymd_date='".$ymd."'");
    while ($row = db_fetch_object($res))
    {
      if ( $row->views*$row->multiplier > $BIGGEST ) {
        $BIGGEST = $row->views*$row->multiplier;
      }
      $STATS[$row->ymd_date][] = $row;
    }
  }
  
  krsort($STATS);
  
  $LAST_DATE = 0;
  $MAX_SIZE = 300;
  foreach($STATS as $date=>$DATELIST)
  {
    foreach($DATELIST as $row)
    {
      if ( $date != $LAST_DATE ) {
        $LAST_DATE = $date;
        if ( $BG == "BGCOLOR='#ECECEC'" ) {
          $BG = "";
        }
        else {
          $BG = "BGCOLOR='#ECECEC'";
        }
      }
      list($year, $month, $day) = sscanf($date, "%4d%2d%2d");
      
      echo "<TR $BG>
              <TD WIDTH='100'><B>$month-$day-$year</B></TD>
              <TD WIDTH='200'>".$row->file_name."</TD>
              <TD WIDTH='300'><DIV STYLE='WIDTH:".((($row->views*$row->multiplier)/$BIGGEST) * $MAX_SIZE)."px;BACKGROUND:#FF0000;'>&nbsp;</DIV></TD>
              <TD WIDTH='100'>".number_format($row->views*$row->multiplier)."</TD>
            </TR>";
    }
  }
  ?>
  </TABLE>
  
  
</DIV>

<I>Pageviews are updated at midnight, server time.</I>
<I>Pageviews are tracked 1 in 10, which is why all numbers are multiples of 10.</I>