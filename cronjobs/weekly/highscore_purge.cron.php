#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $infoRes = db_query("SELECT * FROM game_info WHERE is_live='1'");
  while( $gRow = db_fetch_object($infoRes) )
  {
    $res = db_query("SELECT * FROM highscore_top_100 WHERE game_id='".$gRow->game_id."' ORDER BY highscore DESC LIMIT 1");
    if ( $row = db_fetch_object($res) )
    {
      $uRes = db_query("SELECT * FROM users WHERE username='".$row->username."'");
      if ( $USER_OBJ = db_fetch_object($uRes) ) {
        give_trophy( $USER_OBJ->user_id, $USER_OBJ->trophy_string, $gRow->trophy_weekly_winner );
      }
      db_free_result($uRes);
    }
    db_free_result($res);
  }
  db_free_result($infoRes);

  db_query("TRUNCATE TABLE highscore_top_100");
?>
