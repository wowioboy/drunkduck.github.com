<?
include('community_header.inc.php');

if ( $COMIC_ROW && ($COMIC_ROW->blog_forum_category_id == $_POST['cid']) )
{
  if ( ($USER->user_id != $COMIC_ROW->user_id) && ($USER->user_id != $COMIC_ROW->secondary_author) )
  {
    return;
  }
}
















if ( !$id = create_topic($_POST['cid'], $_POST['topic_name'], $USER->user_id, $_POST['topic_txt']) )
{
  echo 'Your post did not go through. Please try again later.';
}
else
{
  $STICKY = 0;
  if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
  {
    $STICKY    = (int)$_POST['sticky'];
    $IMPORTANT = (int)$_POST['important'];

    if ( $STICKY ) {
      db_query("UPDATE community_topics SET sticky='".$STICKY."' WHERE topic_id='".$id."'");
    }
    if ( $IMPORTANT ) {
      db_query("UPDATE community_topics SET flags=(flags | ".FORUM_FLAG_IMPORTANT.") WHERE topic_id='".$id."'");
    }
  }


  $res = db_query("SELECT post_id FROM community_posts WHERE topic_id='".$id."' ORDER BY post_id DESC LIMIT 1");
  $row = db_fetch_object($res);
  updatePostCt($USER->user_id);
  echo 'Thank you for your post. Click <a href="view_topic.php?tid='.$id.'&'.passables_query_string( array('tid') ).'&pid='.$row->topic_id.'#'.$row->topic_id.'">here</a> to read your post.';

  include_once(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');
  user_update_trophies( $USER, 13 );
}

?><meta http-equiv="refresh" content="2;url=view_topic.php?tid=<?=$id?>&<?=passables_query_string( array('tid') )?>&pid=<?=$row->topic_id?>#<?=$row->topic_id?>">