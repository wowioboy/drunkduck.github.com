#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $res = db_query("SELECT comic_id, comic_name FROM comics");
  while ( $row = db_fetch_object($res) )
  {
    $PAGE_VIEWS   = 0;
    $WEEKLY_VIEWS = 0;
    $TOTAL_DAYS   = 0;

    $res2 = db_query("SELECT views, multiplier, ymd_date FROM page_statistics WHERE page_title='".$row->comic_name."'");
    while ($row2 = db_fetch_object($res2))
    {
      list($yr, $mo, $dy) = sscanf($row2->ymd_date, '%4d%2d%2d');
      $time               = mktime(1,1,1,$mo, $dy, $yr);
      $days = ( time() - $time ) / 60 / 60 / 24;

      if ( $days <= 7 ) {
        $WEEKLY_VIEWS += ($row2->views*$row2->multiplier);
      }

      $PAGE_VIEWS += ($row2->views*$row2->multiplier);
      $TOTAL_DAYS++;
    }
    db_free_result($res2);

    $GROWTH = round(($WEEKLY_VIEWS/7) / ($PAGE_VIEWS/$TOTAL_DAYS), 4 );

    db_query("UPDATE comics SET visits='".$PAGE_VIEWS."', seven_day_growth='".$GROWTH."' WHERE comic_id='".$row->comic_id."'");

    $rangeStart = mktime(0, 0, 0, date("m"), date("d")-8, date("Y"));
    $rangeEnd   = mktime(0, 0, 0, date("m"), date("d")-1,   date("Y"));
    $res2 = db_query("SELECT COUNT(*) as total_uniques FROM unique_comic_tracking WHERE comic_id='".$row->comic_id."' AND ymd_date>'".date("Ymd", $rangeStart)."' AND ymd_date<='".date("Ymd", $rangeEnd)."'");
    $row2 = db_fetch_object($res2);
    db_free_result($res2);

    $SEVEN_DAY_VISITS = $row2->total_uniques;

    $res2 = db_query("SELECT SUM(counter) as total_uniques FROM unique_comic_tracking_archive WHERE comic_id='".$row->comic_id."' AND ymd_date>'".date("Ymd", $rangeStart)."' AND ymd_date<='".date("Ymd", $rangeEnd)."'");
    $row2 = db_fetch_object($res2);
    db_free_result($res2);

    $SEVEN_DAY_VISITS += $row2->total_uniques;

    db_query("UPDATE comics SET seven_day_visits='".$SEVEN_DAY_VISITS."' WHERE comic_id='".$row->comic_id."'");
  }
  db_free_result($res);

?>
