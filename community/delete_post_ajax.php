<?
  define('DEBUG_MODE',    0);

  include('../includes/global.inc.php');
  include('community_functions.inc.php');
  include('community_data.inc.php');


  if ( isset($_GET['comic_id']) ) {
    $CID = (int)$_GET['comic_id'];
  }
  else {
    $CID = (int)$_POST['comic_id'];
  }

  if ( $CID )
  {
    $res = db_query("SELECT * FROM comics WHERE comic_id='".$CID."'");
    $COMIC_ROW = db_fetch_object($res);
    db_free_result($res);
  }





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
    updatePostCt($row->user_id);
    die($row->post_id);
  }
  else {
    db_free_result($res);
  }
  die("0");
?>