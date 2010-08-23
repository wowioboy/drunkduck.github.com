#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');
  
  db_query("UPDATE users SET warning=warning-1 WHERE warning>0");
?>
