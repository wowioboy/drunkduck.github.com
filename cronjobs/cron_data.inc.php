<?
set_time_limit(0);

ob_start();

define('DEBUG_MODE',      0);
$START_TIME = explode(' ', microtime());

require_once('/var/www/html/drunkduck.com/includes/global.inc.php');

register_shutdown_function('trackCron');

function trackCron()
{
  global $START_TIME;
  $FILE_LIST = get_included_files();

  $END_TIME = explode(' ', microtime());

  $T = ($END_TIME[0]+$END_TIME[1]) - ($START_TIME[0]+$START_TIME[1]);

  $DATA  = "Time Taken: ".round($T, 5)." seconds.\n";
  $DATA .= "Ended AT: ".date("l, M j, Y ")." at ".date("g:ia");

  $OUTPUT = '';
  for($i =0; $i<10;$i++) {
    $OUTPUT .= ob_get_contents();
  }

  write_file($DATA."\n\nOutput:\n".$OUTPUT, $FILE_LIST[0].'.RAN');
}
?>
