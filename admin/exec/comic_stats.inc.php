<script src="<?=HTTP_JAVASCRIPT?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<style>
div.autocomplete {
text-align:left;
  position:absolute;
  width:250px;
  background-color:white;
  border:1px solid #6699ff;
  margin:0px;
  padding:0px;
}
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
}
div.autocomplete ul li.selected { background-color: #fffa8c;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  padding:2px;
  cursor:pointer;
  color:#000000;
}

div.autocomplete li span.informal {
  padding-left:10px;
  display:block;
  font-size:9px;
  color:#888;
}

div.autocomplete li span.informal span.informal_rt {
  padding-left:10px;
  display:block;
  font-size:9px;
  color:#888;
  text-align: right;
}
</style>
<?


if ( !$_GET['comic_name'] )
{
  ?>
  <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>
    <DIV CLASS='header' ALIGN='CENTER' style="width:300px;border:1px solid black;">
      Search for Comic
      <br>
      <INPUT TYPE="TEXT" NAME="comic_name" id="comic_name" style="width:300px;"> <INPUT TYPE='SUBMIT' VALUE='View!'>
      <div id="autocomplete_choices" class="autocomplete"></div>
    </DIV>
    <script type="text/javascript">
      new Ajax.Autocompleter("comic_name", "autocomplete_choices", "/xmlhttp/find_comic_for_admin.php", {paramName: "try", minChars: 3, afterUpdateElement: getSelectionId});

      function getSelectionId(text, li) {
        $('comic_name').value = li.id;
      }
    </script>
  </FORM>
  <?
  return;
}

$res = db_query("SELECT * FROM comics WHERE comic_name='".$_GET['comic_name']."'");
if ( !$COMIC_ROW = db_fetch_object($res) )
{
  echo '<div align="center">Comic not found.</div>';
}


$TOTAL_VIEWS = 0;

$thirty_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-30, date("Y") );
$thirty_days_ago = date("Ymd", $thirty_days_ago);

$seven_days_ago = mktime( date("H"), date("i"), 0, date("m"), date("d")-7, date("Y") );
$seven_days_ago = date("Ymd", $seven_days_ago);

$THIRTY_DAY_ARR = array();
$SEVEN_DAY_ARR = array();
$BIGGEST = 0;

  $res = db_query("SELECT * FROM comic_pageviews WHERE comic_id='".$COMIC_ROW->comic_id."'");
  while ( $row = db_fetch_object($res) )
  {
    if ( $row->counter*$row->multiplier > $BIGGEST ) {
      $BIGGEST = $row->counter*$row->multiplier;
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


  if ( false && $USER->user_id == 1 )
  {
    $res = db_query("SELECT DISTINCT(ymd_date) AS ymd FROM unique_comic_tracking_archive WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY ymd_date ASC");
    while($row = db_fetch_object($res) )
    {
      $res2 = db_query("SELECT counter AS total_uniques FROM unique_comic_tracking_archive WHERE comic_id='".$COMIC_ROW->comic_id."' AND ymd_date='".$row->ymd."'");
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

  }
  else
  {
    $res = db_query("SELECT * FROM unique_comic_tracking_archive WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY ymd_date ASC");
    while($row = db_fetch_object($res))
    {
      $UNIQUES_ALL_TIME[$row->ymd_date] += $row->counter;
      if ( $row->ymd_date > $thirty_days_ago ) {
        $UNIQUES_30_DAY[$row->ymd_date] += $row->counter;
      }
      if ( $row->ymd_date > $seven_days_ago ) {
        $UNIQUES_7_DAY[$row->ymd_date] += $row->counter;
      }
    }
  }


  include(WWW_ROOT.'/includes/graph.class.php');

  $FILENAME = 'graph_'.$COMIC_ROW->comic_id.'_'.date("YmdH").'_all_time.png';

    $GRAPH = new PHPGraph(200, 150, 30, $COMIC_ROW->comic_name."'s all time pageviews");

    $LINE1 = new PHPGraph_GraphLine('Views', $GRAPH_ARR);
    $LINE1->setColor(255, 0, 0);
    $GRAPH->addLine($LINE1);

    $LINE2 = new PHPGraph_GraphLine('Unique IP Addresses', $UNIQUES_ALL_TIME);
    $LINE2->setColor(0, 0, 255);
    $GRAPH->addLine($LINE2);

    $GRAPH->createGraph(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME);

  ?><img src="http://images.drunkduck.com/pageview_graph_cache/<?=$FILENAME?>"><?


  $FILENAME = 'graph_'.$COMIC_ROW->comic_id.'_'.date("YmdH").'_month.png';

    $GRAPH = new PHPGraph(200, 150, 30, $COMIC_ROW->comic_name."'s 30 day pageviews");

    $LINE1 = new PHPGraph_GraphLine('Views', $THIRTY_DAY_ARR);
    $LINE1->setColor(255, 0, 0);
    $GRAPH->addLine($LINE1);

    $LINE2 = new PHPGraph_GraphLine('Unique IP Addresses', $UNIQUES_30_DAY);
    $LINE2->setColor(0, 0, 255);
    $GRAPH->addLine($LINE2);

    $GRAPH->createGraph(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME);

  ?><img src="http://images.drunkduck.com/pageview_graph_cache/<?=$FILENAME?>"><?

  $FILENAME = 'graph_'.$COMIC_ROW->comic_id.'_'.date("YmdH").'_week.png';

    $GRAPH = new PHPGraph(200, 150, 30, $COMIC_ROW->comic_name."'s 7 day pageviews");

    $LINE1 = new PHPGraph_GraphLine('Views', $SEVEN_DAY_ARR);
    $LINE1->setColor(255, 0, 0);
    $GRAPH->addLine($LINE1);

    $LINE2 = new PHPGraph_GraphLine('Unique IP Addresses', $UNIQUES_7_DAY);
    $LINE2->setColor(0, 0, 255);
    $GRAPH->addLine($LINE2);

    $GRAPH->createGraph(WWW_ROOT.'/gfx/pageview_graph_cache/'.$FILENAME);

  ?><img src="http://images.drunkduck.com/pageview_graph_cache/<?=$FILENAME?>"><?





























  ?>
  <div>
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

  </div>
  <?




?>