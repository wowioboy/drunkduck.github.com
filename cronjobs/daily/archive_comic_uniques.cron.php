#!/usr/bin/php -q
<?php

  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  ob_end_flush();
  ob_end_flush();
  ob_end_flush();
  ob_end_flush();
  ob_end_flush();
  ob_end_flush();
  ob_implicit_flush(true);

  $tNow = microtime();
  $startTime = explode(" ", $tNow);
  $startTime = $startTime[0] + $startTime[1];
  $killTime = time() + 129600;

  $removed = 0;
  $ct = 1;
//  while(true)
// since this job is kicked off every day, only run it for 36 hours to avoid multiple copies
  while(time() < $killTime)
  {
    print("STARTING...\r\n");
    $comic_res = db_query("SELECT DISTINCT(comic_id), ymd_date FROM unique_comic_tracking WHERE ymd_date<'".date("Ymd")."' LIMIT 25");

    if ( db_num_rows($comic_res) == 0 ) break;

    while( $comic_row = db_fetch_object($comic_res) )
    {
      $date_row = new stdClass();
      $date_row->ymd_date = $comic_row->ymd_date;
      //$date_res = db_query("SELECT DISTINCT(ymd_date) FROM unique_comic_tracking WHERE comic_id='".."' LIMIT 10");
      //while( $date_row = db_fetch_object($date_res) )
      {
        print("#$ct PROCESSING comic_id: ".$comic_row->comic_id." on ".$date_row->ymd_date." ...");

        $res = db_query("SELECT COUNT(*) as total_uniques FROM unique_comic_tracking WHERE comic_id='".$comic_row->comic_id."' AND ymd_date='".$date_row->ymd_date."'");
        $row = db_fetch_object($res);
        db_free_result($res);

        db_query("INSERT INTO unique_comic_tracking_archive (comic_id, ymd_date, counter) VALUES ('".$comic_row->comic_id."', '".$date_row->ymd_date."', '".$row->total_uniques."')");
        if ( db_rows_affected() > 0 ) {
          db_query("DELETE FROM unique_comic_tracking WHERE comic_id='".$comic_row->comic_id."' AND ymd_date='".$date_row->ymd_date."'");
          $removed += db_rows_affected();
        }

      }
      db_free_result($date_res);
      print(" - DONE!\r\n");
      // prevent CPU overload.
      //usleep(100);

      ++$ct;
    }
    db_free_result($comic_res);

    $tNow = microtime();
    $nowTime = explode(" ", $tNow);
    $nowTime = $nowTime[0] + $nowTime[1];

    print("Rows Removed so far: ".number_format($removed)."\r\n");
    print("Time Taken so far: ".number_format($nowTime-$startTime)."\r\n");
    print("Rows/Second: ".number_format( $removed / ($nowTime-$startTime) )."\r\n");
    print("Rows/Minute: ".number_format( $removed / (($nowTime-$startTime)/60) )."\r\n");
    print("Rows/Day: ".number_format( $removed / (($nowTime-$startTime)/86400) )."\r\n");




    print("SLEEPING...\r\n");
    usleep(100);
  }
?>
