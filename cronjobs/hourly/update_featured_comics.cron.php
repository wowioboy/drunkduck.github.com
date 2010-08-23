#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $FEATURES = array();
  
  $res = db_query("SELECT * FROM featured_comics");
  while($row = db_fetch_object($res) )
  {
    $FEATURES[$row->comic_id] = $row->feature_id;
  }
  db_free_result($res);
  
  $res = db_query("SELECT * FROM comics WHERE comic_id IN ('".implode("', '", $FEATURES)."')");
  while($row = db_fetch_object($res) ) {
    db_query("UPDATE featured_comics SET category='".$row->search_category."', style='".$row->search_style."' WHERE feature_id='".$FEATURES[$row->comic_id]."'");
  }
?>
