my_news_update[1]
<?

  $USERS = array();
  $POSTS = array();

  $res = db_query("SELECT admin_blog.blog_id, admin_blog.title, admin_blog.timestamp_date as news_date,users.user_id,users.username FROM admin_blog join users on admin_blog.user_id=users.user_id ORDER BY admin_blog.timestamp_date DESC LIMIT 6");
  while($row = db_fetch_object($res)) {
    $POSTS[]                   = $row;
  }
  db_free_result($res);

  foreach($POSTS as $post) {
    ?><p align="left" onMouseOver="LinkTip.show('<?=$post->blog_id?>', this,'news');" onMouseOut="LinkTip.hide();" ><a  href="http://<?=DOMAIN?>/news/news_archive.php?story=<?=$post->blog_id?>">&nbsp;&nbsp; <?=BBCode($post->title)?></a>
<!--<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Posted by <b><?=$post->username.",</b> on <b>".date("F j, Y, g:i a",$post->news_date)?></b>)-->
</p>

<?
  }
?>
