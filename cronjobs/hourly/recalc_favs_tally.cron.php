#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $FAVS = array();

  $USERS = array();
  $res = db_query("SELECT user_id, flags FROM users");
  while($row = db_fetch_object($res))
  {
    $USERS[$row->user_id] = $row->flags;
  }

  $res = db_query("SELECT user_id, comic_id FROM comic_favs");
  while($row = db_fetch_object($res))
  {
    if ( !($USERS[$row->user_id]->flags & FLAG_FROZEN) ) {
      $FAVS[$row->comic_id]++;
    }
  }

  db_query("DELETE FROM comic_favs_tally");
  foreach($FAVS as $comic_id=>$tally) {
    db_query("INSERT INTO comic_favs_tally (comic_id, tally) VALUES ('".$comic_id."', '".$tally."')");
  }
?>
