#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  db_query("DELETE FROM game_sessions WHERE time_expired<='".( time()-86400 )."'");
?>
