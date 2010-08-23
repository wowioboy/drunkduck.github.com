<DIV STYLE='WIDTH:400px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>Executive Summary:</DIV>

  <?
  $res = db_query("SELECT COUNT(*) as total_users FROM users");
  $row = db_fetch_object($res);
  db_free_result($res);
  echo "<B>Users Signed Up: </B>".number_format($row->total_users)."<BR>";

  $res = db_query("SELECT COUNT(*) as total_comics FROM comics");
  $row = db_fetch_object($res);
  db_free_result($res);
  echo "<B>Comics Hosted: </B>".number_format($row->total_comics)."<BR>";

  $res = db_query("SELECT COUNT(*) as total_comics FROM comics WHERE total_pages>0");
  $row = db_fetch_object($res);
  db_free_result($res);
  echo "<B>Comics with at least 1 page: </B>".number_format($row->total_comics)."<BR>";

  $res = db_query("SELECT COUNT(*) as total_comics FROM comics WHERE total_pages>1");
  $row = db_fetch_object($res);
  db_free_result($res);
  echo "<B>Comics with at least 2 pages: </B>".number_format($row->total_comics)."<BR>";

  $res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages");
  $row = db_fetch_object($res);
  db_free_result($res);
  echo "<B>Total Pages: </B>".number_format($row->total_pages)."<BR>";


  ?>
</DIV>
<?
include(WWW_ROOT.'/includes/graph.class.php');

$LINE1 = new PHPGraph_GraphLine('Uniques');
$LINE1->setColor(255, 0, 0);
$FILTERED_RESULTS = array();
$res = db_query("SELECT * FROM unique_tracking ORDER BY year_date ASC, month_date ASC, day_date ASC");
while($row = db_fetch_object($res))
{
  $YMD = '';
  $YMD .= $row->year_date;
  $YMD .= (($row->month_date>9)?$row->month_date:'0'.$row->month_date);
  $YMD .= (($row->day_date>9)?$row->day_date:'0'.$row->day_date);

  if ( $YMD != date("Ymd") ) {
    $LINE1->addValue($row->day_counter);
    $FILTERED_RESULTS[$YMD] = 0;
  }
}
db_free_result($res);









$LINE1b =  new PHPGraph_GraphLine('Uniques(Filtered)');
$LINE1b->setColor(255, 0, 255);
$res = db_query("SELECT * FROM unique_tracking_filtered ORDER BY year_date ASC, month_date ASC, day_date ASC");
while($row = db_fetch_object($res))
{
  $YMD = '';
  $YMD .= $row->year_date;
  $YMD .= (($row->month_date>9)?$row->month_date:'0'.$row->month_date);
  $YMD .= (($row->day_date>9)?$row->day_date:'0'.$row->day_date);

  if ( $YMD != date("Ymd") ) {
    $FILTERED_RESULTS[$YMD] = $row->day_counter;
  }
}
db_free_result($res);
foreach($FILTERED_RESULTS as $ymd=>$count) {
  $LINE1b->addValue($count);
}







$LINE2 = new PHPGraph_GraphLine('Pageviews');
$LINE2->setColor(0, 0, 255);
$FILTERED_RESULTS = array();
$DISTINCT_RES = db_query("SELECT DISTINCT(ymd_date) FROM global_pageviews ORDER BY ymd_date ASC");
while( $YMD_ROW = db_fetch_object($DISTINCT_RES) )
{
  if ( $YMD_ROW->ymd_date != date("Ymd") )
  {
    $total = 0;
    $res = db_query("SELECT * FROM global_pageviews WHERE ymd_date='".$YMD_ROW->ymd_date."' ORDER BY hour ASC");
    while( $row = db_fetch_object($res) )
    {
      $amt = ($row->counter*$row->multiplier);
      $total += $amt;
    }
    $LINE2->addValue($total);

    $FILTERED_RESULTS[$YMD_ROW->ymd_date] = 0;
  }
}

$LINE2b = new PHPGraph_GraphLine('Pageviews(Filtered)');
$LINE2b->setColor(0, 128, 0);
$DISTINCT_RES = db_query("SELECT DISTINCT(ymd_date) FROM global_pageviews_filtered ORDER BY ymd_date ASC");
while( $YMD_ROW = db_fetch_object($DISTINCT_RES) )
{
  if ( $YMD_ROW->ymd_date != date("Ymd") )
  {
    $total = 0;
    $res = db_query("SELECT * FROM global_pageviews_filtered WHERE ymd_date='".$YMD_ROW->ymd_date."' ORDER BY hour ASC");
    while( $row = db_fetch_object($res) )
    {
      $FILTERED_RESULTS[$YMD_ROW->ymd_date] += ($row->counter*$row->multiplier);
    }
  }
}
foreach($FILTERED_RESULTS as $ymd=>$count) {
  $LINE2b->addValue($count);
}




//$GRAPH = new PHPGraph(400, 300, 30, 'Uniques & Pageviews');
$GRAPH = new PHPGraph(800, 600, 40, 'Uniques & Pageviews');
$GRAPH->addLine($LINE1);
$GRAPH->addLine($LINE1b);
$GRAPH->addLine($LINE2);
$GRAPH->addLine($LINE2b);
$GRAPH->createGraph('exec_graph_visits.png');

?>
<div align="center">
  <img src='exec_graph_visits.png'>
</div>
<?












$res = db_query("SELECT signed_up FROM users");
while($row = db_fetch_object($res))
{
  $SIGNUPS[date("Ymd", $row->signed_up)]++;
}

while( count($SIGNUPS) ) {
  $USERS[] = array_shift($SIGNUPS);
}
$LINE = new PHPGraph_GraphLine('Users', $USERS);
$LINE->setColor(255, 0, 0);
$GRAPH = new PHPGraph(400, 300, 30, 'Signups');
$GRAPH->addLine($LINE);
$GRAPH->createGraph('exec_graph_signups.png');

?>

<div align="center">
  <img src='exec_graph_signups.png'>
</div>

<?











$res = db_query("SELECT created_timestamp FROM comics WHERE created_timestamp != 0");
while($row = db_fetch_object($res))
{
  if ( date("Ymd", $row->created_timestamp) != date("Ymd") ) {
    $COMIC_CREATIONS[date("Ymd", $row->created_timestamp)]++;
  }
}

while( count($COMIC_CREATIONS) ) {
  $COMICS[] = array_shift($COMIC_CREATIONS);
}
$LINE = new PHPGraph_GraphLine('Comic Creations', $COMICS);
$LINE->setColor(255, 0, 0);
$GRAPH = new PHPGraph(400, 300, 30, 'Comics Created Since 08/14/2006');
$GRAPH->addLine($LINE);
$GRAPH->createGraph('exec_graph_comics.png');

?>
<div align="center">
  <img src='exec_graph_comics.png'>
</div>









<?
$comicRSS = Array();
$res = db_query("SELECT * FROM rss_calls ORDER BY ymd_date ASC");
while($row = db_fetch_object($res))
{
  //if ( $row->ymd_date != date("Ymd") )
  {
    $comicRSS[] = $row->counter;
  }
}

$desktopRSS = Array();
$res = db_query("SELECT * FROM rss_calls_desktop_app ORDER BY ymd_date ASC");
while($row = db_fetch_object($res))
{
  //if ( $row->ymd_date != date("Ymd") )
  {
    $desktopRSS[] = $row->counter;
  }
}


$LINE1 = new PHPGraph_GraphLine('Comic Feeds', $comicRSS);
$LINE1->setColor(255, 0, 0);

$LINE2 = new PHPGraph_GraphLine('Desktop App Feeds', $desktopRSS);
$LINE2->setColor(0, 255, 0);


$GRAPH = new PHPGraph(400, 300, 30, 'RSS Feeds Requested');
$GRAPH->addLine($LINE1);
$GRAPH->addLine($LINE2);
$GRAPH->createGraph('exec_rss_feeds.png');

?>
<div align="center">
  <img src='exec_rss_feeds.png'>
</div>
















<?
$SUM = 0;
$downloadArr = Array();
$msi = Array();
$zip = Array();
$res = db_query("SELECT * FROM downloads ORDER BY ymd_date ASC");
while($row = db_fetch_object($res))
{
  if ( !isset($downloadArr[$row->ymd_date]) ) {
    $downloadArr[$row->ymd_date] = 0;
  }
  if ( !isset($zip[$row->ymd_date]) ) {
    $zip[$row->ymd_date] = 0;
  }
  if ( !isset($msi[$row->ymd_date]) ) {
    $msi[$row->ymd_date] = 0;
  }




  if ( $row->ymd_date != date("Ymd") )
  {
    $SUM += $row->counter;
    $downloadArr[$row->ymd_date] += $row->counter;

    if ( strstr($row->filename, '.zip') ) {
      $zip[$row->ymd_date] += $row->counter;
    }
    else {
      $msi[$row->ymd_date] += $row->counter;
    }
  }
}

$LINE1 = new PHPGraph_GraphLine('Total Downloads', $downloadArr);
$LINE1->setColor(255, 0, 0);

$LINE2 = new PHPGraph_GraphLine('Zip Version', $zip);
$LINE2->setColor(0, 255, 0);

$LINE3 = new PHPGraph_GraphLine('Msi Version', $msi);
$LINE3->setColor(0, 0, 255);

$GRAPH = new PHPGraph(400, 300, 30, 'Drunk Duck Alerter Downloads (Total: '.number_format($SUM).')');
$GRAPH->addLine($LINE1);
$GRAPH->addLine($LINE2);
$GRAPH->addLine($LINE3);
$GRAPH->createGraph('exec_rss_downloads.png');

?>
<div align="center">
  <img src='exec_rss_downloads.png'>
</div>














<?
$CPU_LOAD = array();
$MEM_LOAD = array();
$res = db_query("SELECT * FROM load_tracker ORDER BY ymd_date ASC, hour ASC, quarter ASC");
while( $row = db_fetch_object($res) )
{
  $CPU_LOAD[] = $row->cpu_load;
  $MEM_LOAD[] = $row->memory_load;
}

$LINE1 = new PHPGraph_GraphLine('CPU', $CPU_LOAD);
$LINE1->setColor(255, 0, 0);

$LINE2 = new PHPGraph_GraphLine('Memory', $MEM_LOAD);
$LINE2->setColor(0, 0, 255);

$GRAPH = new PHPGraph(300, 200, 30, 'System Load Over All Time');
$GRAPH->addLine($LINE1);
$GRAPH->addLine($LINE2);
$GRAPH->createGraph('exec_load.png');


$CPU_LOAD = array();
$MEM_LOAD = array();
$res = db_query("SELECT * FROM load_tracker WHERE ymd_date='".date("Ymd")."' ORDER BY hour ASC, quarter ASC");
while( $row = db_fetch_object($res) )
{
  $CPU_LOAD[] = $row->cpu_load;
  $MEM_LOAD[] = $row->memory_load;
}


$LINE1 = new PHPGraph_GraphLine('CPU', $CPU_LOAD);
$LINE1->setColor(255, 0, 0);

$LINE2 = new PHPGraph_GraphLine('Memory', $MEM_LOAD);
$LINE2->setColor(0, 0, 255);

$GRAPH = new PHPGraph(300, 200, 30, 'System Load Today');
$GRAPH->addLine($LINE1);
$GRAPH->addLine($LINE2);
$GRAPH->createGraph('exec_load_today.png');
?>

<div align="center">
  <img src='exec_load.png?r=<?=rand(1,9999)?>'><img src='exec_load_today.png?r=<?=rand(1,9999)?>'>
</div>