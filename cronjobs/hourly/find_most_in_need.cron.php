#!/usr/bin/php -q
<?php
  include('/var/www/html/drunkduck.com/cronjobs/cron_data.inc.php');

  $COMIC_ARRAY = array();

  $res = db_query("SELECT comic_id, comic_name, total_pages FROM comics WHERE total_pages>0 AND delisted=0");
  while ($row = db_fetch_object($res))
  {
    $PAGE_VIEWS = 0;

    $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$row->comic_id."'");
    $row2 = db_fetch_object($res2);
    db_free_result($res2);
    $COMMENTS = $row2->total_comments;

    if ( $COMMENTS == 0 ) {
      $COMMENTS = .9;
    }
    $COMIC_ARRAY[$row->comic_id] = ($row->total_pages/$COMMENTS);
  }
  db_free_result($res);

  arsort($COMIC_ARRAY, SORT_NUMERIC);

  db_query("DELETE FROM comics_in_need");

  $ct = 50;
  foreach($COMIC_ARRAY as $id=>$need)
  {
    print( $ct .": INSERT INTO comics_in_need (comic_id, need) VALUES ('".$id."', '".round($need, 5)."')\n");
    db_query("INSERT INTO comics_in_need (comic_id, need) VALUES ('".$id."', '".round($need, 5)."')");
    if ( --$ct==0 ) break;
  }
?>
