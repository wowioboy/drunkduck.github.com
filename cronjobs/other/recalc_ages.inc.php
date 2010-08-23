#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $res = db_query("SELECT * FROM users");
  while($row = db_fetch_object($res) )
  {
    $res2 = db_query("SELECT * FROM demographics WHERE user_id='".$row->user_id."'");
    $row2 = db_fetch_object($res2);
    db_free_result($res2);
    db_query("UPDATE users SET age='".((int)timestampToYears($row2->dob_timestamp))."' WHERE user_id='".$row->user_id."'");
  }
?>
