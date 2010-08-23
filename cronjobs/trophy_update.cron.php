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


include(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');

$ct = 3900;
$db = 78;
while( true )
{
  $res = db_query('SELECT * FROM users LIMIT '.($db * 50).', 50');

  if ( db_num_rows($res) == 0 ) break;

  while( $USER = db_fetch_object($res) )
  {
    print("#$ct - User: ".$USER->username."...");
    include(WWW_ROOT.'/includes/user_maintenance/check_maint.inc.php');
    print("DONE.\r\n");

    ++$ct;

    usleep(100);
  }

  ++$db;
  print("Sleeping");
  for($i=0; $i<10; $i++) {
    usleep("100000");
    print(".");
  }
  print("\r\n");
}

?>
