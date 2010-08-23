#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');
  
  $MASS_RES = db_query("SELECT comic_id FROM comics WHERE total_pages>0");
  while($COMIC_ROW = db_fetch_object($MASS_RES))
  {
    $res        = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND page_score>0");
    $TOTALS_PAGES_ROW = db_fetch_object($res);
    db_free_result($res);
    
    $res        = db_query("SELECT SUM(page_score) as total_score FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
    $TOTALS_SCORE_ROW = db_fetch_object($res);
    db_free_result($res);
    
    $SCORE = round($TOTALS_SCORE_ROW->total_score / $TOTALS_PAGES_ROW->total_pages, 2);
    db_query("UPDATE comics SET rating='".$SCORE."' WHERE comic_id='".$COMIC_ROW->comic_id."'");

  }
  db_free_result($MASS_RES);
?>
