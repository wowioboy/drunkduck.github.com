<?
define('DEBUG_MODE', 0); // keep debug info from polluting response.

include_once('includes/global.inc.php');

// Call to track the unique.
track_waw_unique(
              (bool)( !$_COOKIE['wawtime'] || (date("d") != date("d", $_COOKIE['wawtime'])) ), // Is new Day?
              (bool)( !$_COOKIE['wawtime'] || (date("m") != date("m", $_COOKIE['wawtime'])) ), // Is new Month?
              (bool)( !$_COOKIE['wawtime'] || (date("Y") != date("Y", $_COOKIE['wawtime'])) )  // Is new Year?
            );

// Now set the cookie to the current time..
setcookie('wawtime', time(), time()+31557600, '/', str_replace('www', '', $_SERVER['HTTP_HOST']));


$res = db_query("UPDATE cowboys_waw_pageviews SET counter=counter+1 WHERE ymd_date='".date("Ymd")."' AND hour='".date("G")."'");
if ( db_rows_affected($res) == 0 ) {
  db_query("INSERT INTO cowboys_waw_pageviews (ymd_date, hour, counter, multiplier) VALUES ('".date("Ymd")."', '".date("G")."', '1', '1')");
}










// Tracks unique day/month/year based on what you tell it.
function track_waw_unique($day=false, $month=false, $year=false)
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
      $res = db_query("UPDATE cowboys_waw_uniques SET ".implode(", ", $Q)." WHERE ".$YMD_STRING);
      if ( db_rows_affected($res) == 0 ) {
        db_query("INSERT INTO cowboys_waw_uniques (year_counter, month_counter, day_counter, year_date, month_date, day_date) VALUES ('0', '0', '0', '".date("Y")."', '".date("m")."', '".date("d")."')");
        db_query("UPDATE cowboys_waw_uniques SET ".implode(", ", $Q)." WHERE ".$YMD_STRING);
      }
      db_free_result($res);
    }
  }
}



header("Content-Type: image/gif");
readfile(WWW_ROOT.'/gfx/data_gfx/1px_trans.gif');
?>