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

$res = db_query("SELECT * FROM community_posts WHERE post_id='".(int)$_POST['pid']."'");
if ( $row = db_fetch_object($res) )
{
  db_free_result($res);

  if ( $CAN_MODERATE || ($USER->user_id == $row->user_id) )
  {
    if ( isset($_POST['move_category_id']) && $CAN_MODERATE )
    {
      $res = db_query("SELECT post_id FROM community_posts WHERE topic_id='".$row->topic_id."' ORDER BY post_id ASC LIMIT 1");
      $pRow = db_fetch_object($res);
      db_free_result($res);

      if ( $pRow->post_id == $row->post_id )
      {
        $res2 = db_query("SELECT * FROM community_topics WHERE topic_id='".$PASSABLES['tid']."'");
        if ( $row2 = db_fetch_object($res2) )
        {
          $res3 = db_query("SELECT * FROM community_categories WHERE category_id='".(int)$_POST['move_category_id']."' AND comic_id='".(int)$COMIC_ROW->comic_id."'");
          if ( $row3 = db_fetch_object($res3) )
          {
            $res = db_query("UPDATE community_topics SET category_id='".(int)$_POST['move_category_id']."' WHERE topic_id='".$PASSABLES['tid']."'");
            $PASSABLES['cid'] = (int)$_POST['move_category_id'];

            updateStats( $row2->category_id ); // update the old
            updateStats( (int)$_POST['move_category_id'] ); // update the new
          }
          db_free_result($res3);
        }
        db_free_result($res2);
      }
    }
    else
    {
      db_query("UPDATE community_posts SET post_body='".db_escape_string($_POST['edit_txt'])."', last_edited='".time()."' WHERE post_id='".(int)$_POST['pid']."'");

      if ( $CAN_MODERATE )
      {
        $res = db_query("SELECT * FROM community_topics WHERE topic_id='".$row->topic_id."'");
        $tRow = db_fetch_object($res);
        db_free_result($res);

        $res = db_query("SELECT post_id FROM community_posts WHERE topic_id='".$row->topic_id."' ORDER BY post_id ASC LIMIT 1");
        $pRow = db_fetch_object($res);
        db_free_result($res);

        if ( $pRow->post_id == $row->post_id )
        {
          $tRow->sticky = (int)$_POST['sticky'];
          $LOCKED      = (int)$_POST['locked'];
          $IMPORTANT   = (int)$_POST['important'];

          if ( $LOCKED ) {
            $tRow->flags |= FORUM_FLAG_LOCKED;
          }
          else {
            $tRow->flags &= ~FORUM_FLAG_LOCKED;
          }

          if ( $IMPORTANT ) {
            $tRow->flags |= FORUM_FLAG_IMPORTANT;
          }
          else {
            $tRow->flags &= ~FORUM_FLAG_IMPORTANT;
          }
        }

        db_query("UPDATE community_topics SET sticky='".$tRow->sticky."', flags='".$tRow->flags."' WHERE topic_id='".$row->topic_id."'");
      }
    }

    echo 'Your edit is complete. Click <a href="view_topic.php?'.passables_query_string().'&pid='.$row->post_id.'#'.$row->post_id.'">here</a> to read your post.';
  }
}
?><meta http-equiv="refresh" content="2;url=view_topic.php?<?=passables_query_string()?>&pid=<?=$row->post_id?>#<?=$row->post_id?>">