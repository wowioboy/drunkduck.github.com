<?
if ( defined('LEAN_AND_MEAN') ) return;

// Page views get tracked in multiples of this number.
// It can safely be changed on a PER HOUR basis. Multiplier is stored along with unque day-hour database entry.
$MULTIPLIER = 10;

// This function tracks a pageview for today up to the hour in resolution.
// int $mul - tracking multiplier
function track_pageview($mul)
{
  if ( !isBot() )
  {
    $res = db_query("UPDATE global_pageviews_filtered SET counter=counter+1 WHERE ymd_date='".date("Ymd")."' AND hour='".date("G")."' AND subdomain='".SUBDOMAIN."'");
    if ( db_rows_affected($res) == 0 ) {
      db_query("INSERT INTO global_pageviews_filtered (ymd_date, hour, counter, multiplier, subdomain) VALUES ('".date("Ymd")."', '".date("G")."', '1', '".$mul."', '".SUBDOMAIN."')");
    }
  }
}

function track_page_load()
{
  global $MULTIPLIER;
  global $TITLE;

  srand((double)microtime()*1000000);
  if ( rand(1, $MULTIPLIER) == 1 )
  {
    if ( defined('TEMPLATE_VIEW') ) {
      track_pageview( $MULTIPLIER );
    }

    $Q_COUNT = $GLOBALS['TOTAL_QUERIES'];
    $Q_TIME  = $GLOBALS['TOTAL_QUERY_TIME'];

    // Determine load time now.
    list($eSec, $eMic) = explode(' ', microtime() );
    $L_TIME = ($eSec + $eMic) - $GLOBALS['_PAGE_START'];

    $PAGE_NAME = $_SERVER['PHP_SELF'];
    $PAGE_NAME = str_replace('//', '/', $PAGE_NAME);
    $PAGE_NAME = db_escape_string($PAGE_NAME);

    if ( strstr( strtolower($_SERVER['HTTP_HOST']), USER_DOMAIN ) ) {
      //$PAGE_NAME = 'user.'.$PAGE_NAME;
    }


    $res = db_query("UPDATE page_statistics SET load_time=load_time+".round($L_TIME, 5).", views=views+1, query_count=query_count+".$Q_COUNT.", query_time=query_time+".round($Q_TIME,5)." WHERE file_name='".$PAGE_NAME."' AND ymd_date='".date("Ymd")."'");
    if ( db_rows_affected($res) == 0 ) {
      $res = db_query("INSERT INTO page_statistics (file_name, page_title, load_time, views, multiplier, query_count, query_time, ymd_date) VALUES ".
                      "('".$PAGE_NAME."', '".db_escape_string($TITLE)."', '".round($L_TIME, 5)."', '1', '".$MULTIPLIER."', '".$Q_COUNT."', '".round($Q_TIME,5)."', '".date("Ymd")."')");
    }
  }
}

// If not a bot, set it up to track this page.
if ( !isBot() )
{
  list($sSec, $sMic) = explode(' ', microtime() );
  $GLOBALS['_PAGE_START'] = $sSec + $sMic;

  register_shutdown_function('track_page_load');
}


?>