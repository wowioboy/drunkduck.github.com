<?
include('community_header.inc.php');

  $res = db_query("SELECT COUNT(*) as total_topics FROM community_topics WHERE category_id='".(int)$_GET['cid']."'");
  if ( !$row = db_fetch_object($res) ) return;
  db_free_result($res);

  $TOTAL_RESULTS = $row->total_topics;
  $TOTAL_PAGES   = ceil($TOTAL_RESULTS/$RESULTS_PER_PAGE);
  $P = $_GET['p']-1;
  if ( $P < 0 ) $P = 0;

  if ( $COMIC_ROW ) {
    $res = db_query("SELECT category_id, category_name, flags FROM community_categories WHERE category_id='".(int)$_GET['cid']."' AND comic_id='".$COMIC_ROW->comic_id."'");
  }
  else {
    $res = db_query("SELECT category_id, category_name, flags FROM community_categories WHERE category_id='".(int)$_GET['cid']."'");
  }

  $CAT_VIEWING_ROW = db_fetch_object($res);
  db_free_result($res);

  if ( !$CAT_VIEWING_ROW ) {
    echo '<div align="center">Invalid Request.</div>';
  }

  if ( ($CAT_VIEWING_ROW->comic_id != 0) && ($CAT_VIEWING_ROW->comic_id != $PASSABLES['comic_id']) ) {
    $PASSABLES['comic_id'] = $CAT_VIEWING_ROW->comic_id;
    header("Location: ".$_SERVER['PHP_SELF'].'?'.passables_query_string());
  }

  if ( ($CAT_VIEWING_ROW->flags&FORUM_FLAG_ADMIN_ONLY) && !($USER->flags & FLAG_IS_ADMIN) ) return;

  if ( ($CAT_VIEWING_ROW->flags&FORUM_FLAG_MOD_ONLY) && !($USER->flags & FLAG_IS_MOD) && !($USER->flags & FLAG_IS_ADMIN) ) return;


$NAV = '<table cellpadding="0" cellspacing="0" class="community_hdr" width="50%" align="center">
          <tr>
            <td width="33%" align="center">
              '.( ($P>0)?'<a href="'.$_SERVER['PHP_SELF'].'?p='.($P).'&'.passables_query_string().'">Previous '.$RESULTS_PER_PAGE.'</a>':'Previous '.$RESULTS_PER_PAGE ).'
            </td>
            <form action="'.$_SERVER['PHP_SELF'].'" method="GET">
            <td width="33%" align="center">
              Page <input type="text" name="p" value="'.number_format($P+1).'" style="width: 20px; height: 12px; border:0px; font-size:10px; text-align:center;"> of <a href="'.$_SERVER['PHP_SELF'].'?p='.$TOTAL_PAGES.'&'.passables_query_string().'">'.number_format($TOTAL_PAGES).'</a>
            </td>
            '.passables_hidden_field().'
            </form>
            <td width="33%" align="center">
              '.( ($P<$TOTAL_PAGES-1)?'<a href="'.$_SERVER['PHP_SELF'].'?p='.($P+2).'&'.passables_query_string().'">Next '.$RESULTS_PER_PAGE.'</a>':'Next '.$RESULTS_PER_PAGE ).'
            </td>
          </tr>
        </table>';


if ( $COMIC_ROW && ($COMIC_ROW->blog_forum_category_id == $CAT_VIEWING_ROW->category_id) )
{
  if ( ($USER->user_id == $COMIC_ROW->user_id) || ($USER->user_id == $COMIC_ROW->secondary_author) )
  {
    $BUTTONS = '<div align="left" class="community_general_container">
                  <a href="create_topic.php?'.passables_query_string().'">Create A New Blog Topic</a>
                </div>';
  }
  else
  {
    $BUTTONS = '<div align="left" class="community_general_container">
                  Cannot Create Topics here.
                </div>';
  }
}
else {
  $BUTTONS = '<div align="left" class="community_general_container">
                <a href="create_topic.php?'.passables_query_string().'">Create A New Topic</a>
              </div>';
}


?>

<div class="community_div">

  <div align="left" class="community_general_container">
    &raquo; <a href="index.php?<?=passables_query_string()?>">View Categories</a>
    &raquo; <?=$CAT_VIEWING_ROW->category_name?>
  </div>

  <?=$NAV?>

  <br>

  <table cellpadding="0" cellspacing="0" class="community_general_container">
    <tr>
      <td width="500" align="left">
        <div class='community_hdr'>
          Topic Name
        </div>
      </td>
      <td width="100" align="center">
        <div class='community_hdr'>
          Post Count
        </div>
      </td>
      <td width="100" align="center">
        <div class='community_hdr'>
          Last Post
        </div>
      </td>
    </tr>
  <?

  if ( ((int)$_GET['cid'] == 244) && ($USER->forum_post_ct < 50) )
  {
    ?>
    <tr>
      <td align="center" colspan="3">
        <div class='community_hdr'>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <i>Only logged-in users with at least 50 posts may participate in this section of the forum.</i>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
      </td>
    </tr>
    <?
  }
  else
  {
    $res = db_query("SELECT * FROM community_topics WHERE category_id='".(int)$_GET['cid']."' ORDER BY sticky DESC, last_post_date DESC LIMIT ".($RESULTS_PER_PAGE*$P).",".$RESULTS_PER_PAGE);
    while( $row = db_fetch_object($res) )
    {
      $POSTS[] = $row;
      $USERS[$row->last_user_id] = $row->last_user_id;
    }
    db_free_result($res);


    $res = db_query("SELECT user_id, username FROM users WHERE user_id IN('".implode("','", $USERS)."')");
    while( $row = db_fetch_object($res) ) {
      $USERS[$row->user_id] = $row;
    }
    db_free_result($res);

    $ct = 0;
    if ( count($POSTS) )
    {
      foreach($POSTS as $post)
      {
        if ( !($post->flags&FORUM_FLAG_ADMIN_ONLY) || ($USER->flags & FLAG_IS_ADMIN) )
        {
          if ( !($post->flags&FORUM_FLAG_MOD_ONLY) || ($USER->flags & FLAG_IS_MOD) )
          {
            if ( $post->last_post_id == 0 )
            {
              $res = db_query("SELECT * FROM community_posts WHERE topic_id='".$post->topic_id."' ORDER BY post_id DESC LIMIT 1");
              $row = db_fetch_object($res);
              db_free_result($res);

              $post->last_post_id = $row->post_id;
              db_query("UPDATE community_topics SET last_post_id='".$post->last_post_id."' WHERE topic_id='".$post->topic_id."'");
            }
            //$JUMP_LINK = "<br><A HREF='view_topic.php?cid=".$post->category_id."&tid=".$post->topic_id."&pid=".$post->last_post_id."#".$post->last_post_id."'><img src='".IMAGE_HOST."/community_gfx/icon_jump.gif' border='0' title='Jump to latest!' alt='Jump to latest!'></A>";


            $bg = '';
            if ( ++$ct%2==0 ) {
               $bg = 'bgcolor="#001D37"';
            }
            ?>
            <tr <?=$bg?>>
              <td width="500" align="left" class="community_thrd">
                <?
                $IS_NEW = false;
                // If the post has happened since this session object was first created
                if ( $COMMUNITY_SESSION->session_start < $post->last_post_date )
                {
                   // If this topic has an entry that means it's been viewed at some point.
                   if ( isset( $COMMUNITY_SESSION->viewed_topics[$post->category_id][$post->topic_id] ) )
                   {
                     // It's been tracked, so if it was tracked at a time earlier to this post:
                     if ( $COMMUNITY_SESSION->viewed_topics[$post->category_id][$post->topic_id] < $post->last_post_date )
                     {
                       $IS_NEW = true;
                     }

                   }
                   else
                   {
                     // It's never been viewed, so it's new!
                     $IS_NEW = true;
                   }
                }
                if ( $IS_NEW ) {
                  ?><div style="font-size:9px;">*NEW*</div><?
                }
                ?>
                <a href="view_topic.php?tid=<?=$post->topic_id?>&<?=passables_query_string( array('tid') )?>" <?=( ($post->flags & FORUM_FLAG_IMPORTANT)?'style="color:#e83939;"':'' )?>><?=htmlentities(html_entity_decode($post->topic_name), ENT_QUOTES)?></a><?=( ($post->flags & FORUM_FLAG_LOCKED)?" *LOCKED*":"")?>
                <?=( ($post->sticky)?'<div class="community_sticky">sticky</div>':'' )?>
                <?
                $PAGES = ceil( $post->post_ct/$RESULTS_PER_PAGE );
                $PAGE_ARR = array();
                for($x=1; ($x<$PAGES+1) && ($x<6); $x++) {
                  $PAGE_ARR[] = '<a href="view_topic.php?cid='.$post->category_id.'&tid='.$post->topic_id.'&p='.$x.'" style="font-size:9px;color:#666666">'.$x.'</a>';
                }

                $PAGES_ARR_EXTRA = '';
                if ( $x <= $PAGES ) {
                  $PAGES_ARR_EXTRA = ' ... <a href="view_topic.php?cid='.$post->category_id.'&tid='.$post->topic_id.'&p='.$PAGES.'" style="font-size:9px;color:#666666">'.$PAGES.'</a>';
                }
                ?>
                <div style="font-size:9px;color:#666666;margin-left:20px;" align="left">Page: <?=implode(", ", $PAGE_ARR).$PAGES_ARR_EXTRA?></div>
              </td>
              <td width="100" align="center" class="community_thrd">
                <?
                if ( $post->post_ct >= 50 ) {
                  ?><font style="color:#000000;font-weight:bold;"><?=number_format($post->post_ct)?></font><?
                }
                else if ( $post->post_ct >= 30 ) {
                  ?><font style="color:#454545;font-weight:bold;"><?=number_format($post->post_ct)?></font><?
                }
                else if ( $post->post_ct >= 10 ) {
                  ?><font style="color:#888888;font-weight:bold;"><?=number_format($post->post_ct)?></font><?
                }
                else {
                  ?><font style="color:#888888;"><?=number_format($post->post_ct)?></font><?
                }
                ?>
              </td>
              <td width="100" align="center" class="community_thrd_last"><?=( ($post->last_post_date==0)?"Never":date($POST_DATE_FORMAT, $post->last_post_date).' by '.username($USERS[$post->last_user_id]->username))?><?=$JUMP_LINK?></td>
            </tr>
            <?
          }
        }
      }
    }
    else
    {
      ?>
      <tr>
        <td align="center" colspan="3">
          <div class='community_hdr'>
            <i>No Posts Yet...</i>
          </div>
        </td>
      </tr>
      <?
    }
  }
  ?>
  </table>

  <br>

  <?=$NAV?>

  <br>

  <?=$BUTTONS?>

</div>


<?
include('community_footer.inc.php');
?>