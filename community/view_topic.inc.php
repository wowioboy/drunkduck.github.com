<?
include('community_header.inc.php');

  $res        = db_query("SELECT * FROM community_topics WHERE topic_id='".(int)$_GET['tid']."'");
  if ( !$TOPIC_VIEWING_ROW = db_fetch_object($res) ) return;
  db_free_result($res);

  if ( $COMIC_ROW ) {
    $res = db_query("SELECT category_name, flags, comic_id FROM community_categories WHERE category_id='".$TOPIC_VIEWING_ROW->category_id."' AND comic_id='".$COMIC_ROW->comic_id."'");
  }
  else {
    $res = db_query("SELECT category_name, flags, comic_id, category_id FROM community_categories WHERE category_id='".$TOPIC_VIEWING_ROW->category_id."'");
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

  $res = db_query("SELECT COUNT(*) as total_posts FROM community_posts WHERE topic_id='".(int)$_GET['tid']."'");
  $row = db_fetch_object($res);
  db_free_result($res);

  $TOTAL_RESULTS = $row->total_posts;
  $TOTAL_PAGES   = ceil($TOTAL_RESULTS/$RESULTS_PER_PAGE);

  if ( isset($_GET['pid']) )
  {
    $res = db_query("SELECT COUNT(*) as total_posts FROM community_posts WHERE topic_id='".(int)$_GET['tid']."' AND post_id<='".(int)$_GET['pid']."'");
    $row = db_fetch_object($res);
    $TOTAL_POSTS_PREVIOUS = $row->total_posts;

    $_GET['p'] = ceil($TOTAL_POSTS_PREVIOUS/$RESULTS_PER_PAGE);
  }

  $P = $_GET['p']-1;
  if ( $P < 0 ) $P = 0;

  if ( $_GET['last_page'] == 1 ) {
    $P = $TOTAL_PAGES-1;
  }




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

if ( $TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED )
{
  $BUTTONS = '<div align="left" class="community_general_container">
                *Locked*
              </div>';
}
else
{
$BUTTONS = '<div align="left" class="community_general_container">
              <a href="create_reply.php?'.passables_query_string().'">Reply</a>
            </div>';
}
?>


<script language="JavaScript">
function editSig( sigID, sigNow, uid )
{
  var sigForm = '';
  sigForm += '<form action="process_sig.php" method="POST">';
  sigForm += '<textarea name="new_sig" rows="5" style="width:100%;">'+sigNow+'</textarea>';
  sigForm += '<div align="center"><input type="submit" value="Submit!"></div>';
  sigForm += '<?=passables_hidden_field()?>';
  sigForm += '<input type="hidden" name="user_id" value="'+uid+'">';
  sigForm += '</form>';
  $('sig_'+sigID).innerHTML = sigForm;
}
</script>

<div class="community_div">

  <div align="left" class="community_general_container">
    &raquo; <a href="index.php?<?=passables_query_string()?>">View Categories</a>
    &raquo; <a href="view_category.php?<?=passables_query_string()?>"><?=htmlentities($CAT_VIEWING_ROW->category_name, ENT_QUOTES)?></a>
    &raquo; <?=$TOPIC_VIEWING_ROW->topic_name?><?=( ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED)?" *LOCKED*":"")?>
  </div>

  <?=$NAV?>

  <br>

<script language="JavaScript">
  function delPost(postID)
  {
    if ( confirm("Are you sure you want to delete this post?") ) {
      postID = Number(postID);
      <?
      if ( $COMIC_ROW )
      {
        ?>ajaxCall('/community/delete_post_ajax.php?comic_id=<?=$COMIC_ROW->comic_id?>&pid='+postID, delResp, true);<?
      }
      else
      {
        ?>ajaxCall('/community/delete_post_ajax.php?pid='+postID, delResp, true);<?
      }
      ?>
    }
  }

  function delResp( resp )
  {
    if ( Number(resp) > 0 ) {
      $('post_'+resp).style.display = 'none';
    }
  }
</script>

  <table cellpadding="0" cellspacing="0" width="100%" class="community_postcont">
  <?
  if ( ($CAT_VIEWING_ROW->category_id == 244) && ($USER->forum_post_ct < 50) )
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

    $res = db_query("SELECT * FROM community_posts WHERE topic_id='".(int)$_GET['tid']."' ORDER BY post_id ASC LIMIT ".($RESULTS_PER_PAGE*$P).",".$RESULTS_PER_PAGE);
    while( $row = db_fetch_object($res) )
    {
      $POSTS[] = $row;
      $USER_LIST[$row->user_id] = $row->user_id;
    }
    db_free_result($res);

    $res = db_query("SELECT * FROM users WHERE user_id IN ('".implode("','", $USER_LIST)."')");
    while($row = db_fetch_object($res)) {
      $USER_LIST[$row->user_id] = $row;
    }

    foreach($POSTS as $post )
    {
      $u = $USER_LIST[$post->user_id];
      ?>
      <tr id="post_<?=$post->post_id?>">
        <td align="center" valign="top" width="100" class="community_user">
          <a name="<?=$post->post_id?>" id="<?=$post->post_id?>"></a>
          <div align="center">
            <a href="http://<?=USER_DOMAIN?>/<?=$u->username?>"><img src="<?=IMAGE_HOST?>/process/user_<?=$u->user_id?>.<?=$u->avatar_ext?>" style="border:1px solid black;max-width:100px"></a>
          </div>


          <?
          if ( $u )
          {
            if (($u->user_id == $COMIC_ROW->user_id) || ($u->user_id == $COMIC_ROW->secondary_author))
            {
              ?>
              <div align="center" class="community_mod">
                <?=username( $u->username )?>
                <br>
                <?=forumTitle($u->user_id)?>
                Comic Admin
                <br>
                <a href="http://<?=DOMAIN?>/community/message/author.php?to=<?=$u->username?>"><img src="<?=IMAGE_HOST?>/community_gfx/icon_sendpq.gif" border="0" alt="Send a private quack!" title="Send a private quack!"></a>
              </div>
              <?
            }
            else if ( ($u->flags & FLAG_IS_ADMIN) )
            {
              ?>
              <div align="center" class="community_admin">
                <?=username( $u->username )?>
                <br>
                <?=forumTitle($u->user_id)?>
                Admin
                <br>
                <a href="http://<?=DOMAIN?>/community/message/author.php?to=<?=$u->username?>"><img src="<?=IMAGE_HOST?>/community_gfx/icon_sendpq.gif" border="0" alt="Send a private quack!" title="Send a private quack!"></a>
              </div>
              <?
            }
            else if ( ($u->flags & FLAG_IS_MOD) )
            {
              ?>
              <div align="center" class="community_mod">
                <?=username( $u->username )?>
                <br>
                <?=forumTitle($u->user_id)?>
                Moderator
                <br>
                <a href="http://<?=DOMAIN?>/community/message/author.php?to=<?=$u->username?>"><img src="<?=IMAGE_HOST?>/community_gfx/icon_sendpq.gif" border="0" alt="Send a private quack!" title="Send a private quack!"></a>
              </div>
              <?
            }
            else if ( ($u->flags & FLAG_FROZEN) || ($u->flags & FLAG_BANNED_PC) || ($u->warning > 100) )
            {
              ?>
              <div align="center" class="community_banned">
                <?=username( $u->username )?>
                <br>
                <?=forumTitle($u->user_id)?>
                Banned
              </div>
              <?
            }
            else
            {
              ?>
              <div align="center">
                <?=username( $u->username )?>
                <br>
                <?=forumTitle($u->user_id)?>
                <a href="http://<?=DOMAIN?>/community/message/author.php?to=<?=$u->username?>"><img src="<?=IMAGE_HOST?>/community_gfx/icon_sendpq.gif" border="0" alt="Send a private quack!" title="Send a private quack!"></a>
              </div>
              <?
            }
            ?>
            <table border="0" cellpadding="0" cellspacing="2" width="100%">
              <tr>
                <td align="right" style="font-size:9px;">Member:</td><td align="left" style="font-size:9px;"><?=number_format($post->user_id)?></td>
              </tr>
              <tr>
                <td align="right" style="font-size:9px;">Posts:</td><td align="left" style="font-size:9px;"><?
                  if ( $u->forum_post_ct == 0 ) {
                    $u->forum_post_ct =  updatePostCt($u->user_id);
                  }
                  echo number_format($u->forum_post_ct);
                  ?></td>
              </tr>
              <tr>
                <td align="right" style="font-size:9px;">Joined:</td><td align="left" style="font-size:9px;"><?=date($JOINED_DATE_FORMAT, $u->signed_up)?></td>
              </tr>
              <tr>
                <td align="right" style="font-size:9px;">Seen:</td><td align="left" style="font-size:9px;"><?=date($JOINED_DATE_FORMAT, $u->last_seen)?></td>
              </tr>
            </table>
            <?
          }
          else
          {
            ?>
            <div align="center">
              <?="Anonymous"?>
            </div>
            <?
          }
          ?>

        </td>
        <td align="left" valign="top" <?=( ($_GET['cid']=='244' && ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED))?"BACKGROUND=\"".IMAGE_HOST."/td_attack.gif\"":"")?>>

          <div class="date" style="width: 100%;" align="right">
              <?=date($POST_DATE_FORMAT, $post->date_created )?>
              | <a href="create_reply.php?quote=<?=$post->post_id?>&<?=passables_query_string()?>">Quote</a>
              <?

              if ( $COMIC_ROW )
              {
                // If they are the owner of the comic or an admin or it's their post
                if ( ($USER->user_id == $COMIC_ROW->user_id) || ($USER->user_id == $COMIC_ROW->secondary_author) ||
                     ($USER->user_id == $post->user_id) || ($USER->flags & FLAG_IS_ADMIN) ||
                     ($USER->flags & FLAG_IS_MOD) )
                {
                  ?>
                  | <a href="edit_post.php?pid=<?=$post->post_id?>&<?=passables_query_string( array('pid') )?>">Edit</a>
                  | <a href="#" onClick="delPost(<?=$post->post_id?>);return false;">Delete</a>
                  <?
                }
              }
              else
              {
                // If they are an admin or it's their post
                if ( ($USER->user_id == $post->user_id) || ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
                {
                  ?>
                  | <a href="edit_post.php?pid=<?=$post->post_id?>&<?=passables_query_string( array('pid') )?>">Edit</a>
                  | <a href="#" onClick="delPost(<?=$post->post_id?>);return false;">Delete</a>
                  <?
                }
              }

              ?>
              | <a href="<?=$_SERVER['PHP_SELF']?>?pid=<?=$post->post_id?>&<?=passables_query_string()?>#<?=$post->post_id?>" alt="Link to this post!" title="Link to this post!">&darr;</a>
          </div>

          <hr />

          <div class="community_post" <?=( ($_GET['cid']=='244' && ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED))?"style=\"background:url(".IMAGE_HOST."/td_attack.gif)\"":"")?>>
            <?=nl2br(  community_bb_code( htmlentities(html_entity_decode($post->post_body), ENT_QUOTES) ) )?>
          </div>


          <?
          if ( $post->last_edited != 0 )
          {
            ?>
            <br>
            <div align="right">
              <i>This post was last edited on <?=date($POST_DATE_FORMAT, $post->last_edited)?></i>
            </div>
            <?
          }
          ?>
          <hr />
          <div class="community_signature" id="sig_<?=$post->post_id?>">
            <?=nl2br( community_bb_code( htmlentities($USER_LIST[$post->user_id]->signature, ENT_QUOTES) ) )?>
          </div>
          <?
          if ( ($USER->user_id == $post->user_id) || ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
          {
            $TMP = trim($USER_LIST[$post->user_id]->signature);
            $TMP = str_replace('\\', '\\\\', $TMP);
            $TMP = str_replace('\'', '\\\'', $TMP);
            $TMP = htmlentities($TMP, ENT_QUOTES);
            $TMP = str_replace("\n", '\n', $TMP);
            $TMP = str_replace("\r", '\r', $TMP);
            ?><div align="center"><a href="#" onClick="JavaScript:editSig('<?=$post->post_id?>', '<?=$TMP?>', '<?=$post->user_id?>');return false;" style="font-size:9px;">Edit Signature</a></div><?
          }
          ?>
        </td>
      </tr>
      <?
      if ( ++$ct < count($POSTS) )
      {
        ?>
        <tr>
          <td colspan="2" style="background:#000000;border:0px;">
            <div style='font-size:1px;height:10px;'>&nbsp;</div>
          </td>
        </tr>
        <?
      }
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