<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');

if ( $_POST['category_id'] > 0 )
{
  $res = db_query("SELECT * FROM community_categories WHERE category_id='".(int)$_POST['category_id']."'");
  if ( $row = db_fetch_object($res) )
  {

    if ( $row->comic_id > 0 )
    {
      header("Location: http://".DOMAIN."/community/view_category.php?comic_id=".$row->comic_id."&cid=".$row->category_id);
    }
    else
    {
      header("Location: http://".DOMAIN."/community/view_category.php?cid=".$row->category_id);
    }


  }
}


if ( $_POST['topic_id'] > 0 )
{
  $res = db_query("SELECT category_id, topic_id FROM community_topics WHERE topic_id='".(int)$_POST['topic_id']."'");
  if ( $tRow = db_fetch_object($res) )
  {
    db_free_result($res);

    $res = db_query("SELECT category_id, comic_id FROM community_categories WHERE category_id='".$tRow->category_id."'");
    if ( $cRow = db_fetch_object($res) )
    {
      db_free_result($res);

      if ( $row->comic_id > 0 )
      {
        header("Location: http://".DOMAIN."/community/view_topic.php?comic_id=".$cRow->comic_id."&cid=".$cRow->category_id."&tid=".$tRow->topic_id);
      }
      else
      {
        header("Location: http://".DOMAIN."/community/view_topic.php?cid=".$cRow->category_id."&tid=".$tRow->topic_id);
      }

    }
  }
}

?>