<?
include('header_edit_comic.inc.php');






















$TOTAL_VIEWS = 0;

$thirty_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-30, date("Y") );
$thirty_days_ago = date("Ymd", $thirty_days_ago);

$seven_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-7, date("Y") );
$seven_days_ago = date("Ymd", $seven_days_ago);

$THIRTY_DAY_ARR = array();
$SEVEN_DAY_ARR = array();
$BIGGEST = 0;
$BIGGEST_DATE = 0;

$res = db_query("SELECT * FROM comic_pageviews WHERE comic_id='".$COMIC_ROW->comic_id."'");
while ( $row = db_fetch_object($res) )
{
  if ( $row->counter*$row->multiplier > $BIGGEST ) {
    $BIGGEST      = $row->counter*$row->multiplier;
    $BIGGEST_DATE = $row->ymd_date;
  }
    if ( !isset($STATS[$row->ymd_date]) || (($row->counter * $row->multiplier) > ($STATS[$row->ymd_date]->views * $STATS[$row->ymd_date]->multiplier)) ) {
      $STATS[$row->ymd_date] = $row;
    }

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

list($bigYear, $bigMonth, $bigDay) = sscanf($BIGGEST_DATE, '%4d%2d%2d');

$AVERAGE_DAY = round( $TOTAL_VIEWS / count($STATS), 2);

?>
  <DIV CLASS='container' ALIGN='LEFT'>
    <DIV CLASS='header' ALIGN='CENTER'>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><h3 align="left">Comic Stats: </h3></td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>Comic launched on: </strong></td>
          <td class="community_thrd"><?=date("m-d-Y", $COMIC_ROW->created_timestamp)?></td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>DrunkDuck Rank: </strong></td>
          <td class="community_thrd">
          <?
          $res = db_query("SELECT COUNT(*) as rank FROM comics WHERE seven_day_visits>'".$COMIC_ROW->seven_day_visits."' AND comic_type='".$COMIC_ROW->comic_type."'");
          $row = db_fetch_object($res);
          db_free_result($res);
          ?>
          <strong>#<?=number_format($row->rank+1)?></strong> in <?=$COMIC_STYLES[$COMIC_ROW->comic_type]?>
          <?
          $res = db_query("SELECT COUNT(*) as rank FROM comics WHERE seven_day_visits>'".$COMIC_ROW->seven_day_visits."'");
          $row = db_fetch_object($res);
          db_free_result($res);
          ?>
          <strong>#<?=number_format($row->rank+1)?></strong> Overall
          </td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>Total Pageviews: </strong></td>
          <td class="community_thrd"><?=number_format($TOTAL_VIEWS)?><?/*number_format($COMIC_ROW->visits)*/?></td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>Biggest single day: </strong></td>
          <td class="community_thrd"><?=number_format($BIGGEST)?> on <?=$bigMonth?>/<?=$bigDay?>/<?=$bigYear?></td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>Average Pageviews per day: </strong></td>
          <td class="community_thrd"><?=number_format($AVERAGE_DAY)?></td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>Total Comments: </strong></td>
          <td class="community_thrd">
            <?
            $res = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
            $row = db_fetch_object($res);
            db_free_result($res);
            $TOTAL_COMMENTS_ON_PAGES = $row->total_comments;
            echo number_format($TOTAL_COMMENTS_ON_PAGES);
            ?>
          </td>
        </tr>
        <tr>
          <td width="30%" class="community_thrd"><strong>Average comments per page: </strong></td>
          <td class="community_thrd">
            <?
            $res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
            $row = db_fetch_object($res);
            db_free_result($res);
            $TOTAL_PAGES = $row->total_pages;
            echo round($TOTAL_COMMENTS_ON_PAGES/$TOTAL_PAGES, 2);
            ?>
          </td>
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
        foreach($STATS as $date=>$row)
        {
          list($year, $month, $day) = sscanf($date, "%4d%2d%2d");
          ?>
          <TR>
            <TD WIDTH='100'><B><?=$month?>-<?=$day?>-<?=$year?></B></TD>
            <TD><DIV STYLE='WIDTH:<?=((($row->counter*$row->multiplier)/$BIGGEST) * $MAX_SIZE)?>px;BACKGROUND:#FF0000;'>&nbsp;</DIV></TD>
            <TD WIDTH='80'><?=number_format($row->counter*$row->multiplier)?></TD>
          </TR>
          <?
          $total += $row->counter * $row->multiplier;
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