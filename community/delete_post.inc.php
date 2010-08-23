<?
  include('community_header.inc.php');

  $CAN_MODERATE = false;
  if ( $COMIC_ROW )
  {
    // If they are the owner of the comic or an admin or it's their post
    if ( ($USER->user_id == $COMIC_ROW->user_id) || ($USER->user_id == $COMIC_ROW->secondary_author) ||
         ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
    {
      $CAN_MODERATE = true;
    }
  }
  else if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
  {
    $CAN_MODERATE = true;
  }

  $res = db_query("SELECT post_id, topic_id, user_id FROM community_posts WHERE post_id='".(int)$_GET['pid']."'");
  if ( ($row = db_fetch_object($res)) && ( ($USER->user_id == $row->user_id) || $CAN_MODERATE ) )
  {
    db_free_result($res);
    delete_post( $row->post_id );

    $res = db_query("SELECT topic_id, last_post_id FROM community_topics WHERE topic_id='".$row->topic_id."'");
    if ( $row = db_fetch_object($res) )
    {
      ?>Click <a href="view_topic.php?tid=<?=$row->topic_id?>&<?=passables_query_string( array('tid') )?>&pid=<?=$row->last_post_id?>#<?=$row->last_post_id?>">here</a> to return to the forum thread.
      <meta http-equiv="refresh" content="2;url=view_topic.php?tid=<?=$row->topic_id?>&<?=passables_query_string( array('tid') )?>&pid=<?=$row->last_post_id?>#<?=$row->last_post_id?>"><?
    }
    else
    {
      ?>Click <a href="index.php?<?=passables_query_string()?>">here</a> to return to the forum.
      <meta http-equiv="refresh" content="2;url=index.php?<?=passables_query_string()?>"><?
    }
  }
  else {
    db_free_result($res);
  }
?>