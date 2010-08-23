#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');
  
  $MASS_RES = db_query("SELECT comic_id FROM comics");
  while($COMIC_ROW = db_fetch_object($MASS_RES))
  {
    $res        = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND post_date<='".time()."'");
    $TOTALS_ROW = db_fetch_object($res);
    db_free_result($res);

    db_query("UPDATE comics SET total_pages='".$TOTALS_ROW->total_pages."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
  db_free_result($MASS_RES);
  
?>
