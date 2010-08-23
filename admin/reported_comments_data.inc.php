<?
define('COMMENT_OKAY', 1);
define('COMMENT_BAD',  2);


function okay_comment($comment_id) 
{
  $comment_id = (int)$comment_id;
  $res = db_query("SELECT * FROM page_comments WHERE comment_id='".$comment_id."'");
  $row = db_fetch_object($res);
  $row->flags = takeFlag( $row->flags, COMMENT_UNDER_REVIEW );
  $row->flags = takeFlag( $row->flags, COMMENT_DELETED );
  $row->flags = giveFlag( $row->flags, COMMENT_APPROVED );
  db_query("UPDATE page_comments SET flags='".$row->flags."' WHERE comment_id='".$comment_id."'");
  
  if ( $row->user_id && db_rows_affected()>0 ) {
    $karma = get_karma_object($row->user_id);
    $karma->comments_reported--;
    db_query("UPDATE karma_tracking SET comments_reported='".$karma->comments_reported."' WHERE user_id='".$karma->user_id."'");
  }
}

function bad_comment($comment_id) 
{
  $comment_id = (int)$comment_id;
  $res = db_query("SELECT * FROM page_comments WHERE comment_id='".$comment_id."'");
  $row = db_fetch_object($res);
  $row->flags = takeFlag( $row->flags, COMMENT_UNDER_REVIEW );
  $row->flags = takeFlag( $row->flags, COMMENT_APPROVED );
  $row->flags = giveFlag( $row->flags, COMMENT_DELETED );
  db_query("UPDATE page_comments SET flags='".$row->flags."' WHERE comment_id='".$comment_id."'");
  
  if ( $row->user_id && db_rows_affected()>0 ) {
    $karma = get_karma_object($row->user_id);
    $karma->comments_erased++;
    db_query("UPDATE karma_tracking SET comments_erased='".$karma->comments_erased."' WHERE user_id='".$karma->user_id."'");
  }
}

function undo_handle($comment_id)
{
  $comment_id = (int)$comment_id;
  $res = db_query("SELECT * FROM page_comments WHERE comment_id='".$comment_id."'");
  $row = db_fetch_object($res);
  $row->flags = takeFlag( $row->flags, COMMENT_DELETED );
  $row->flags = takeFlag( $row->flags, COMMENT_APPROVED );
  $row->flags = giveFlag( $row->flags, COMMENT_UNDER_REVIEW );
  db_query("UPDATE page_comments SET flags='".$row->flags."' WHERE comment_id='".$comment_id."'");
  
  if ( $row->user_id && db_rows_affected()>0 ) {
    $karma = get_karma_object($row->user_id);
    $karma->comments_reported++;
    db_query("UPDATE karma_tracking SET comments_reported='".$karma->comments_reported."' WHERE user_id='".$karma->user_id."'");
  }
}

function undo_report($comment_id)
{
  $comment_id = (int)$comment_id;
  $res = db_query("SELECT * FROM page_comments WHERE comment_id='".$comment_id."'");
  $row = db_fetch_object($res);
  $row->flags = takeFlag( $row->flags, COMMENT_DELETED );
  $row->flags = takeFlag( $row->flags, COMMENT_APPROVED );
  $row->flags = takeFlag( $row->flags, COMMENT_UNDER_REVIEW );
  db_query("UPDATE page_comments SET flags='".$row->flags."' WHERE comment_id='".$comment_id."'");

  if ( $row->user_id && db_rows_affected()>0 ) {
    $karma = get_karma_object($row->user_id);
    $karma->comments_reported++;
    db_query("UPDATE karma_tracking SET comments_reported='".$karma->comments_reported."' WHERE user_id='".$karma->user_id."'");
  }  
  
  db_query("DELETE FROM comment_reports WHERE comment_id='".$comment_id."'");
}
?>