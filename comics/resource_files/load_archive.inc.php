<?
/****************************
*                           *
*    LOAD ALL PAGE DATA!    *
*                           *
****************************/

if ( isset($_GET['p']) ) {
  $PAGE_ID = (int)$_GET['p'];
}
else if ( isset($_POST['p'])) {
  $PAGE_ID = (int)$_POST['p'];
}
else {
  $PAGE_ID = false;
}

if ( $PAGE_ID !== false )
{
  $res = db_query("SELECT * FROM comic_pages WHERE page_id='".$PAGE_ID."' AND comic_id='".$COMIC_ROW->comic_id."' AND post_date<='".time()."' ORDER BY order_id DESC LIMIT 1");
  if ($PAGE_ROW = db_fetch_object($res)) {
    $PAGE_ID = $PAGE_ROW->page_id;
  }
  db_free_result($res);
}

// If no page id was specified
if ( $PAGE_ID === false )
{
  $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND post_date<='".time()."' ORDER BY order_id DESC LIMIT 1");
  $PAGE_ROW = db_fetch_object($res);
  db_free_result($res);
}


// If they are on an actual comic page....
if ( $PAGE_ROW )
{
    $PAGE_LIST = array();
    $res = db_query("SELECT page_id, order_id, post_date, page_title, is_chapter FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."' AND post_date<='".time()."' ORDER BY order_id DESC");
    while ($row = db_fetch_object($res) ) {
      $PAGE_LIST[$row->order_id] = $row;
    }
    db_free_result($res);

    $res       = db_query("SELECT * FROM users WHERE user_id='".$PAGE_ROW->user_id."'");
    $OWNER_ROW = db_fetch_object($res);
    db_free_result($res);

    if ( $USER )
    {
      if ( ($USER->user_id == $COMIC_ROW->user_id) || ($USER->user_id == $COMIC_ROW->secondary_author) || ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) ) {
        $ALLOW_MODERATION = true;
      }
      else {
        $ALLOW_MODERATION = false;
      }
    }

    $PAGE_TAGS = array();
    $res = db_query("SELECT tag FROM tags_by_page WHERE comic_id='".$COMIC_ROW->comic_id."' AND page_id='".$PAGE_ROW->page_id."' ORDER BY counter DESC LIMIT 10");
    if ( db_num_rows($res) == 0 ) {
      db_free_result($res);
      $res = db_query("SELECT tag FROM tags_by_comic WHERE comic_id='".$COMIC_ROW->comic_id."'");
    }

    while($row = db_fetch_object($res))
    {
      $PAGE_TAGS[] = $row->tag;
    }

    /***********************************
    *                                  *
    *         AUTO BOOKMARK            *
    *                                  *
    ***********************************/
    /*
    if ( $USER && $PAGE_ROW && isset($COMIC_FAVS[$COMIC_ROW->comic_id]) )
    {
      if ( $COMIC_FAVS[$COMIC_ROW->comic_id]->bookmark_page_id != $PAGE_ROW->page_id )
      {
        db_query("UPDATE comic_favs SET bookmark_page_id='".$PAGE_ROW->page_id."' WHERE user_id='".$USER->user_id."' AND comic_id='".$COMIC_ROW->comic_id."'");
      }
    }
    */

    /***********************************
    *                                  *
    * DONE LOADING ALL IMPORTANT DATA! *
    *                                  *
    ***********************************/
}
/*var_dump(__LINE__ . ':' . basename(__FILE__), $_GET, $PAGE_ROW, $COMIC_ROW); exit;*/
if ( !$PAGE_ROW || !$COMIC_ROW->total_pages )
{
  echo("This comic has no pages yet!");
  return;
}


/***********************************
*                                  *
*    PROCESS POSTED DATA HERE!     *
*                                  *
***********************************/

// Handle page comments, if they are on a comic page.
if ( $PAGE_ROW )
{
    if ( isset($_POST['com2']) && ($USER->flags & FLAG_VERIFIED) )
    {
      $rating  = 0;
      $vWeight = 0;
      if ( $USER &&
          ($USER->user_id != $COMIC_ROW->user_id) &&
          ($USER->user_id != $COMIC_ROW->secondary_author) &&
          ($USER->ip != $OWNER_ROW->ip) )
      {
        $res = db_query("SELECT * FROM page_comments WHERE page_id='".$PAGE_ROW->page_id."' AND (user_id='".$USER->user_id."' OR ip='".$_SERVER['REMOTE_ADDR']."') AND vote_rating>0");
        if ( db_num_rows($res) == 0 ) {
          $rating  = $_POST['vote_rating'];
          $vWeight = $USER->vote_weight;
        }
        db_free_result($res);
      }

      if ( $rating > 5 ) $rating = 5;

      $flags = 0;
      if ( $_POST['anonymous'] || !$USER ) {
        $flags |= COMMENT_ANONYMOUS;
      }

      $comment = strip_tags(trim($_POST['comment']));

      if ( (strlen($comment)==0) && ($rating==0) )
      {
        echo "<DIV ALIGN='CENTER' STYLE='FONT-SIZE:14px;COLOR:#FF0000;BACKGROUND:#000000;'><B>You didn't say anything!</B></DIV>";
      }
      else
      {
        preg_match_all('/[^\[\]\/ ]{50}/', $comment, $FOUND);
        $FOUND = $FOUND[0];
        if ( count($FOUND) ) {
          $FOUND = array_unique($FOUND);
          foreach($FOUND as $str) {
            $comment = str_replace($str, $str.' ', $comment);
          }
        }

        db_query("INSERT INTO page_comments (comic_id, page_id, user_id, post_date, flags, comment, vote_rating, vote_weight, ip) VALUES ('".$COMIC_ROW->comic_id."', '".$PAGE_ROW->page_id."', '".(int)$USER->user_id."', '".time()."', '".$flags."', '".db_escape_string($comment)."', '".$rating."', '".$vWeight."', '".$_SERVER['REMOTE_ADDR']."')");

        if ( $USER && ($USER->user_id != $COMIC_ROW->user_id) && ($USER->user_id != $COMIC_ROW->secondary_author) )  {
          add_comment_karma($USER->user_id, 1);
        }

        recalcPageScore($PAGE_ROW->page_id);

        header("Location: http://".DOMAIN."/".$FOLDER_NAME."/?p=".$PAGE_ROW->page_id);
      }
    }
}
else // Since there is no page being viewed, this must be the 'home page'
{
  // process home page post data
}

/***********************************
*                                  *
*   DONE PROCESSING POSTED DATA    *
*                                  *
***********************************/




/***********************************
*                                  *
*    PROCESS GET'ED DATA HERE!     *
*                                  *
***********************************/

if ( $ALLOW_MODERATION )
{
  // if they are viewing a page ( not the home page )
  if ( $PAGE_ROW )
  {
      if ( isset($_GET['mute']) ) {
        $res = db_query("SELECT * FROM page_comments WHERE comment_id='".(int)$_GET['mute']."' AND comic_id='".$COMIC_ROW->comic_id."'");
        if ( $row = db_fetch_object($res) ) {
          db_query("UPDATE page_comments SET flags='".($row->flags | COMMENT_MUTED)."' WHERE comment_id='".(int)$_GET['mute']."'");

          if ( $row->user_id && db_rows_affected()>0 ) {
            $karma = get_karma_object($row->user_id);
            $karma->comments_muted++;
            db_query("UPDATE karma_tracking SET comments_muted='".$karma->comments_muted."' WHERE user_id='".$karma->user_id."'");
          }
        }
        db_free_result($res);
      }
      else if ( isset($_GET['unmute']) ) {
        $res = db_query("SELECT * FROM page_comments WHERE comment_id='".(int)$_GET['unmute']."' AND comic_id='".$COMIC_ROW->comic_id."'");
        if ( $row = db_fetch_object($res) ) {
          db_query("UPDATE page_comments SET flags='".($row->flags & ~COMMENT_MUTED)."' WHERE comment_id='".(int)$_GET['unmute']."'");

          if ( $row->user_id && db_rows_affected()>0) {
            $karma = get_karma_object($row->user_id);
            $karma->comments_muted--;
            db_query("UPDATE karma_tracking SET comments_muted='".$karma->comments_muted."' WHERE user_id='".$karma->user_id."'");
          }
        }
        db_free_result($res);
      }
      else if ( isset($_GET['report']) ) {
        $res = db_query("SELECT * FROM page_comments WHERE comment_id='".(int)$_GET['report']."' AND comic_id='".$COMIC_ROW->comic_id."'");
        if ( $row = db_fetch_object($res) ) {
          db_query("UPDATE page_comments SET flags='".($row->flags | COMMENT_UNDER_REVIEW)."' WHERE comment_id='".(int)$_GET['report']."'");
          db_query("INSERT INTO comment_reports ( comment_id, reported_user_id, reported_ip ) VALUES ('".(int)$_GET['report']."', '".$row->user_id."', '".$row->ip."')");

          if ( $row->user_id && db_rows_affected()>0 ) {
            $karma = get_karma_object($row->user_id);
            $karma->comments_reported++;
            db_query("UPDATE karma_tracking SET comments_reported='".$karma->comments_reported."' WHERE user_id='".$karma->user_id."'");
          }
        }
        db_free_result($res);
      }
  }
  else
  {
    // process GET data for home page.
  }
}

/***********************************
*                                  *
*   DONE PROCESSING GET'ED DATA    *
*                                  *
***********************************/

require_once(WWW_ROOT.'/comics/resource_files/template_functions/dd_tag_functions.inc.php');
require_once(WWW_ROOT.'/comics/resource_files/template_functions/dd_tags_archive.inc.php');

/*die($COMIC_ROW->template . 'http://drunkduck.com'.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');*/
/*var_dump('http://drunkduck.com'.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd'); exit;*/
if ( $COMIC_ROW->template != 0 ) {
  $TEMPLATE = file_get_contents('http://drunkduck.com'.'/comics/resource_files/templates/'.$COMIC_ROW->template.'/template.dd');
}
elseif ( ($response = file_get_contents('http://drunkduck.com'.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd'))
    && strpos($http_response_header[0], ' 200 ') !== false) {
/*    var_dump(  strpos(array_shift($http_response_header), ' 200 ') !== false ); exit;     */
    $TEMPLATE = $response;    
/*    var_dump('custom');*/
/*    var_dump( $response ); exit;*/
    
}
else {
/*    die('ddddddddddddddddddddddddd');*/
    
    $TEMPLATE = file_get_contents('http://drunkduck.com'.'/comics/resource_files/default_template.new.dd');
}

?>