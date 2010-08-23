<?


if ( defined('LEAN_AND_MEAN') ) return;
// Call to track the unique.
track_unique(
              (bool)( !$_COOKIE['ttime'] || (date("d") != date("d", $_COOKIE['ttime'])) ), // Is new Day?
              (bool)( !$_COOKIE['ttime'] || (date("m") != date("m", $_COOKIE['ttime'])) ), // Is new Month?
              (bool)( !$_COOKIE['ttime'] || (date("Y") != date("Y", $_COOKIE['ttime'])) )  // Is new Year?
            );

// Now set the cookie to the current time..
setcookie('ttime', time(), time()+31557600, '/', str_replace('www', '', $_SERVER['HTTP_HOST']));

// Tracks unique day/month/year based on what you tell it.
function track_unique($day=false, $month=false, $year=false)
{
  $Q = array();

  if ( $day ) { // Track unique daily visit?
    $Q[] = 'day_counter=day_counter+1';
  }

  if ( $month ) { // Track unique monthly visit?
    $Q[] = 'month_counter=month_counter+1';
  }

  if ( $year ) { // Track unique yearly visit?
    $Q[] = 'year_counter=year_counter+1';
  }

  if ( count($Q) ) { // Anything?
    $YMD_STRING = "year_date=".date("Y")." AND month_date=".date("m")." AND day_date=".date("d");

    // MWright 12/11/06: Updated to post header data on each request.
    //@db_query("insert into stats_analysis (headerText) values ('" . str_replace("'", "''", $_SERVER["HTTP_USER_AGENT"]) . "')");

    if ( !isBot() )
    {
      $res = db_query("UPDATE unique_tracking_filtered SET ".implode(", ", $Q)." WHERE ".$YMD_STRING);
      if ( db_rows_affected($res) == 0 ) {
        db_query("INSERT INTO unique_tracking_filtered (year_counter, month_counter, day_counter, year_date, month_date, day_date) VALUES ('0', '0', '0', '".date("Y")."', '".date("m")."', '".date("d")."')");
        db_query("UPDATE unique_tracking_filtered SET ".implode(", ", $Q)." WHERE ".$YMD_STRING);
      }
      db_free_result($res);

      db_query("UPDATE referral_tracking SET counter=counter+1 WHERE referrer='".db_escape_string($_SERVER['HTTP_REFERRER'])."' AND ymd_date='".date("Ymd")."'");
      if ( db_rows_affected() < 1 ) {
        db_query("INSERT INTO referral_tracking (referrer, counter, ymd_date) VALUES ('".db_escape_string($_SERVER['HTTP_REFERRER'])."', '1', '".date("Ymd")."')");
      }
    }
/*
    $res = db_query("UPDATE unique_tracking SET ".implode(", ", $Q)." WHERE ".$YMD_STRING);
    if ( db_rows_affected($res) == 0 ) {
      db_query("INSERT INTO unique_tracking (year_counter, month_counter, day_counter, year_date, month_date, day_date) VALUES ('0', '0', '0', '".date("Y")."', '".date("m")."', '".date("d")."')");
      db_query("UPDATE unique_tracking SET ".implode(", ", $Q)." WHERE ".$YMD_STRING);
    }
    db_free_result($res);
*/
  }
}

?>