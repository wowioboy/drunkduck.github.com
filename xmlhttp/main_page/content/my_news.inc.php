my_news[1]
<?
  $res      = db_query("SELECT ymd_date FROM admin_blog WHERE ymd_date<='".YMD."' ORDER BY ymd_date DESC LIMIT 1");
  $row      = db_fetch_object($res);
  $BLOG_YMD = $row->ymd_date;
  db_free_result($res);

  $USERS = array();
  $POSTS = array();

  $res = db_query("SELECT blog_id, title, timestamp_date FROM admin_blog WHERE ymd_date='".$BLOG_YMD."' ORDER BY timestamp_date DESC");
  while($row = db_fetch_object($res)) {
    $POSTS[]                   = $row;
  }
  db_free_result($res);

  foreach($POSTS as $post) {
    ?><p align="left"><a href="http://<?=DOMAIN?>/news/news_archive.php?story=<?=$post->blog_id?>">[<b>DD</b>] <?=BBCode($post->title)?></a></p><?
  }
?>