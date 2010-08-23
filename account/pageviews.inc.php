<?
if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];

$res = db_query("SELECT * FROM comics WHERE comic_id='".$CID."'");
$COMIC_ROW = db_fetch_object($res);

if ( !$COMIC_ROW || (!($USER->flags & FLAG_IS_ADMIN) && ($COMIC_ROW->user_id != $USER->user_id) && ($COMIC_ROW->secondary_author != $USER->user_id)) ) {
  db_free_result($res);
  return;
}
db_free_result($res);




    $TOTAL_VIEWS = 0;

    $thirty_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-30, date("Y") );
    $thirty_days_ago = date("Ymd", $thirty_days_ago);

    $seven_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-7, date("Y") );
    $seven_days_ago = date("Ymd", $seven_days_ago);

    $THIRTY_DAY_ARR = array();
    $SEVEN_DAY_ARR = array();
    $BIGGEST = 0;
    $res = db_query("SELECT * FROM page_statistics WHERE page_title='".$COMIC_ROW->comic_name."'");
    while ( $row = db_fetch_object($res) )
    {
      if ( $row->views*$row->multiplier > $BIGGEST ) {
        $BIGGEST = $row->views*$row->multiplier;
      }
      $STATS[$row->ymd_date] = $row;

      $GRAPH_ARR[$row->ymd_date] += ($row->views * $row->multiplier);

      if ( $row->ymd_date > $thirty_days_ago ) {
        $THIRTY_DAY_ARR[$row->ymd_date] += ($row->views * $row->multiplier);
      }
      if ( $row->ymd_date > $seven_days_ago ) {
        $SEVEN_DAY_ARR[$row->ymd_date] += ($row->views * $row->multiplier);
      }

      $TOTAL_VIEWS += ($row->views * $row->multiplier);
    }

    krsort($STATS);
    ksort($GRAPH_ARR);
    ksort($THIRTY_DAY_ARR);
    ksort($SEVEN_DAY_ARR);







if ( $USER->username == 'Volte6' )
{
  $UNIQUES_ALL_TIME = array();
  $UNIQUES_30_DAY   = array();
  $UNIQUES_7_DAY    = array();

  foreach($GRAPH_ARR as $ymd=>$trash)
  {
    $UNIQUES_ALL_TIME[$ymd] = 0;
  }

  foreach($THIRTY_DAY_ARR as $ymd=>$trash)
  {
    $UNIQUES_30_DAY[$ymd] = 0;
  }

  foreach($SEVEN_DAY_ARR as $ymd=>$trash)
  {
    $UNIQUES_7_DAY[$ymd] = 0;
  }



  $res = db_query("SELECT DISTINCT(ymd_date) AS ymd FROM unique_comic_tracking WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY ymd_date ASC");
  while($row = db_fetch_object($res) )
  {
    $res2 = db_query("SELECT COUNT(*) AS total_uniques FROM unique_comic_tracking WHERE comic_id='".$COMIC_ROW->comic_id."' AND ymd_date='".$row->ymd."'");
    $row2 = db_fetch_object($res2);
    db_free_result($res2);

    $UNIQUES_ALL_TIME[$row->ymd] += $row2->total_uniques;
    if ( $row->ymd > $thirty_days_ago ) {
      $UNIQUES_30_DAY[$row->ymd] += $row2->total_uniques;
    }
    if ( $row->ymd > $seven_days_ago ) {
      $UNIQUES_7_DAY[$row->ymd] += $row2->total_uniques;
    }
  }
  db_free_result($res);



  include(WWW_ROOT.'/includes/graph.class.php');

  $FILENAME = 'graph_'.$COMIC_ROW->comic_id.'_'.date("YmdH").'_all_time.png';
  if ( !file_exists(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME) )
  {
    $GRAPH = new PHPGraph(400, 300, 30, $COMIC_ROW->comic_name."'s all time pageviews");

    $LINE1 = new PHPGraph_GraphLine('Views', $GRAPH_ARR);
    $LINE1->setColor(255, 0, 0);
    $GRAPH->addLine($LINE1);

    $LINE2 = new PHPGraph_GraphLine('Unique IP Addresses', $UNIQUES_ALL_TIME);
    $LINE2->setColor(0, 0, 255);
    $GRAPH->addLine($LINE2);

    $GRAPH->createGraph(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME);
  }
  ?><br><br><img src="<?=IMAGE_HOST?>/pageview_graph_cache/<?=$FILENAME?>"><?


  $FILENAME = 'graph_'.$COMIC_ROW->comic_id.'_'.date("YmdH").'_month.png';
  if ( !file_exists(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME) )
  {
    $GRAPH = new PHPGraph(400, 300, 30, $COMIC_ROW->comic_name."'s 30 day pageviews");

    $LINE1 = new PHPGraph_GraphLine('Views', $THIRTY_DAY_ARR);
    $LINE1->setColor(255, 0, 0);
    $GRAPH->addLine($LINE1);

    $LINE2 = new PHPGraph_GraphLine('Unique IP Addresses', $UNIQUES_30_DAY);
    $LINE2->setColor(0, 0, 255);
    $GRAPH->addLine($LINE2);

    $GRAPH->createGraph(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME);
  }
  ?><br><img src="<?=IMAGE_HOST?>/pageview_graph_cache/<?=$FILENAME?>"><?

  $FILENAME = 'graph_'.$COMIC_ROW->comic_id.'_'.date("YmdH").'_week.png';
  if ( !file_exists(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME) )
  {
    $GRAPH = new PHPGraph(400, 300, 30, $COMIC_ROW->comic_name."'s 7 day pageviews");

    $LINE1 = new PHPGraph_GraphLine('Views', $SEVEN_DAY_ARR);
    $LINE1->setColor(255, 0, 0);
    $GRAPH->addLine($LINE1);

    $LINE2 = new PHPGraph_GraphLine('Unique IP Addresses', $UNIQUES_7_DAY);
    $LINE2->setColor(0, 0, 255);
    $GRAPH->addLine($LINE2);

    $GRAPH->createGraph(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME);
  }
  ?><br><img src="<?=IMAGE_HOST?>/pageview_graph_cache/<?=$FILENAME?>"><?
}
else
{
  ?>
  <SPAN CLASS='headline'><?=$COMIC_ROW->comic_name?>'s Pageviews</SPAN>

  <DIV STYLE='WIDTH:500px;' CLASS='container' ALIGN='LEFT'>
    <DIV CLASS='header' ALIGN='CENTER'>Breakdown:</DIV>

    <TABLE BORDER='0' WIDTH='100%' CELLPADDING='0' CELLSPACING='0'>
    <?
    $MAX_SIZE = 300;
    foreach($STATS as $date=>$row)
    {
      list($year, $month, $day) = sscanf($date, "%4d%2d%2d");

      echo "<TR>
              <TD WIDTH='100'><B>$month-$day-$year</B></TD>
              <TD WIDTH='300'><DIV STYLE='WIDTH:".((($row->views*$row->multiplier)/$BIGGEST) * $MAX_SIZE)."px;BACKGROUND:#FF0000;'>&nbsp;</DIV></TD>
              <TD WIDTH='100'>".number_format($row->views*$row->multiplier)."</TD>
            </TR>";
    }
    ?>
      <TR>
        <TD WIDTH='100'>TOTAL</TD>
        <TD WIDTH='300'>&nbsp;</TD>
        <TD WIDTH='100'><?=number_format($TOTAL_VIEWS)?></TD>
      </TR>
    </TABLE>

  </DIV>
  <?
}
?>