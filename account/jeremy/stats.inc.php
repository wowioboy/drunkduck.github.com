<link href="../acctStyles.css" rel="stylesheet" type="text/css" />
<div class="pagecontent">
<?

$ALLOWED_USER_IDS = array(12638, 29991, 44681, 7858, 33045, 46601);

if ( !($USER->flags & FLAG_IS_ADMIN) && !in_array($USER->user_id, $ALLOWED_USER_IDS) ) return;




















$TOTAL_VIEWS = 0;

$thirty_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-30, date("Y") );
$thirty_days_ago = date("Ymd", $thirty_days_ago);

$seven_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-7, date("Y") );
$seven_days_ago = date("Ymd", $seven_days_ago);

$THIRTY_DAY_ARR = array();
$SEVEN_DAY_ARR = array();
$BIGGEST = 0;
$BIGGEST_DATE = 0;
$MONTHS        = array();

$dTotal         = 0;
$dNow           = 0;

$res = db_query("SELECT * FROM cowboys_waw_pageviews");
while ( $row = db_fetch_object($res) )
{
  list($y, $m, $d) = sscanf($row->ymd_date, '%4d%2d$2d');

  $MONTHS[$y.'-'.$m] += ($row->counter*$row->multiplier);

  if ( $dNow != $row->ymd_date )
  {
    if ( $dTotal > $BIGGEST ) {
      $BIGGEST      = $dTotal;
    }

    $dNow   = $row->ymd_date;
    $dTotal = 0;
  }

  $STATS[$row->ymd_date] += ($row->counter * $row->multiplier);

  $GRAPH_ARR[$row->ymd_date] += ($row->counter * $row->multiplier);

  if ( $row->ymd_date > $thirty_days_ago ) {
    $THIRTY_DAY_ARR[$row->ymd_date] += ($row->counter * $row->multiplier);
  }
  if ( $row->ymd_date > $seven_days_ago ) {
    $SEVEN_DAY_ARR[$row->ymd_date] += ($row->counter * $row->multiplier);
  }

  $TOTAL_VIEWS += ($row->counter * $row->multiplier);
  $dTotal      += ($row->counter * $row->multiplier);
}

krsort($STATS);
ksort($GRAPH_ARR);
ksort($THIRTY_DAY_ARR);
ksort($SEVEN_DAY_ARR);

$AVERAGE_DAY = round( $TOTAL_VIEWS / count($STATS), 2);

?>
  <DIV CLASS='container' ALIGN='LEFT'>
    <DIV CLASS='header' ALIGN='CENTER'>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><h3 align="left">Comic Stats for all Cowboys and Aliens Content: </h3></td>
        </tr>
        <tr>
          <td colspan="2"><h4 align="left">Pageview Breakdown:</h4></td>
          </tr>
      </table>
      </DIV>

    <TABLE WIDTH='720' BORDER='0' CELLPADDING='2' CELLSPACING='0' id="pageviews">
        <?
        $total = 0;
        $MAX_SIZE = 500;
        $monthNow = -1;
        foreach($STATS as $date=>$amt)
        {
          list($year, $month, $day) = sscanf($date, "%4d%2d%2d");

          if ( $monthNow == -1 ) {
            $monthNow = $m;
          }

          if( $monthNow != $m )
          {
            echo "<TR>
                   <TD ALIGN='LEFT' WIDTH='100'>&nbsp;</TD>
                   <TD ALIGN='RIGHT' WIDTH='600'><B>Month ".$month." Total</B>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
                   <TD ALIGN='LEFT' WIDTH='100'>".number_format($MONTHS[$year.'-'.$month])."</TD>
                  </TR>";


            $monthNow = $m;
          }


          ?>
          <TR>
            <TD WIDTH='100'><B><?=$month?>-<?=$day?>-<?=$year?></B></TD>
            <TD><DIV STYLE='WIDTH:<?=( ($amt/$BIGGEST) * $MAX_SIZE)?>px;BACKGROUND:#FF0000;'>&nbsp;</DIV></TD>
            <TD WIDTH='80'><?=number_format($amt)?></TD>
          </TR>
          <?
          $total += $amt;
        }
        ?>
      <TR>
        <TD WIDTH='100'>TOTAL</TD>
        <TD>&nbsp;</TD>
        <TD WIDTH='80'><?=number_format($total)?></TD>
      </TR>
    </TABLE>

  </DIV>


<?
include('footer_edit_comic.inc.php');
return;
?>








































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
      if ( $row->counter*$row->multiplier > $BIGGEST ) {
        $BIGGEST = $row->counter*$row->multiplier;
      }
      $STATS[$row->ymd_date] = $row;

      $GRAPH_ARR[$row->ymd_date] += ($row->counter * $row->multiplier);

      if ( $row->ymd_date > $thirty_days_ago ) {
        $THIRTY_DAY_ARR[$row->ymd_date] += ($row->counter * $row->multiplier);
      }
      if ( $row->ymd_date > $seven_days_ago ) {
        $SEVEN_DAY_ARR[$row->ymd_date] += ($row->counter * $row->multiplier);
      }

      $TOTAL_VIEWS += ($row->counter * $row->multiplier);
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
              <TD WIDTH='300'><DIV STYLE='WIDTH:".((($row->counter*$row->multiplier)/$BIGGEST) * $MAX_SIZE)."px;BACKGROUND:#FF0000;'>&nbsp;</DIV></TD>
              <TD WIDTH='100'>".number_format($row->counter*$row->multiplier)."</TD>
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
</div>