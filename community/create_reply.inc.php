<?
include('community_header.inc.php');


if ( isset($_GET['quote']) )
{
  $res = db_query("SELECT user_id, post_body FROM community_posts WHERE post_id='".(int)$_GET['quote']."'");
  if ( $row = db_fetch_object($res) )
  {
    db_free_result($res);

    $QUOTE['body'] = $row->post_body;
    $QUOTE['username'] = $row->user_id;

    $res = db_query("SELECT username FROM users WHERE user_id='".$QUOTE['username']."'");
    $row = db_fetch_object($res);
    db_free_result($res);
    $QUOTE['username'] = $row->username;


    $QUOTE_TXT = htmlentities("[quote=\"".$QUOTE['username']."\"]\n".$QUOTE['body']."\n[/quote]", ENT_QUOTES);
  }
}

$res = db_query("SELECT category_id, topic_name, flags FROM community_topics WHERE topic_id='".(int)$_GET['tid']."'");
$TOPIC_VIEWING_ROW = db_fetch_object($res);
db_free_result($res);

$res = db_query("SELECT category_id, category_name FROM community_categories WHERE category_id='".$TOPIC_VIEWING_ROW->category_id."'");
$CAT_VIEWING_ROW = db_fetch_object($res);
db_free_result($res);

?>

<div class="community_div" align="center">

  <div align="left" class="community_general_container">
    &raquo; <a href="index.php?<?=passables_query_string()?>">View Categories</a>
    &raquo; <a href="view_category.php?<?=passables_query_string()?>"><?=htmlentities($CAT_VIEWING_ROW->category_name, ENT_QUOTES)?></a>
    &raquo; <a href="view_topic.php?<?=passables_query_string()?>"><?=htmlentities($TOPIC_VIEWING_ROW->topic_name, ENT_QUOTES)?></a>
    &raquo; Reply<?=( ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED)?" *LOCKED*":"")?>
  </div>

<?

if ( ($CAT_VIEWING_ROW->category_id == 244) && ($USER->forum_post_ct < 50) )
{
  ?>
  <div class='community_hdr'>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <i>Only logged-in users with at least 50 posts may participate in this section of the forum.</i>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <?
  return;
}

if ( ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED) )
{
  ?><div align="center">This forum thread is LOCKED</div><?
}
else
{
  ?>
  <form action="process_reply.php" method="POST">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">

      <tr>
        <td align="left">
          <div class='community_hdr'>
            Reply
          </div>
        </td>
      </tr>

      <tr>
        <td align="left">
          Body
          <br>
          <script language="JavaScript">
              textareaName = 'reply_txt';
          </script>
          <script src="bbcode.js" language="JavaScript"></script>
          <textarea id="reply_txt" name="reply_txt" rows="20" style="width:99%" ><?=$QUOTE_TXT?></textarea>
        </td>
      </tr>
      <tr>
        <td align="left">
          <a href="http://<?=DOMAIN?>/community/emotes.php" target="_blank">Emotes</a>
        </td>
      </tr>
      <tr>
        <td align="right">
          <input type="submit" value="Send!">
        </td>
      </tr>

    </table>
    <?=passables_hidden_field()?>
  </form>

  </div>

  <div style="width:100%;height:500px;overflow:auto;">
    <!-- <table cellpadding="0" cellspacing="0" width="95%" class="community_postcont"> -->
    <?
    $res = db_query("SELECT * FROM community_posts WHERE topic_id='".(int)$_GET['tid']."' ORDER BY post_id DESC LIMIT 50");
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
      <table cellpadding="0" cellspacing="0" width="95%" class="community_postcont">
      <tr>
        <td align="center" valign="top" width="100" class="community_user">

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
        <td align="left" valign="top">

          <div class="date" style="width: 100%;" align="right">
              <?=date($POST_DATE_FORMAT, $post->date_created )?>
              | <a href="create_reply.php?quote=<?=$post->post_id?>&<?=passables_query_string()?>">Quote</a>
              <?
              if ( ($USER->user_id == $post->user_id) || ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
              {
                ?>
                | <a href="edit_post.php?pid=<?=$post->post_id?>&<?=passables_query_string( array('pid') )?>">Edit</a>
                <?
              }
              ?>
              | <a href="delete_post.php?pid=<?=$post->post_id?>&<?=passables_query_string( array('pid') )?>" onClick="confirm('Are you SURE you want to delete this post?');">Delete</a>
          </div>

          <hr />

          <div class="community_post">
            <?=nl2br(  community_bb_code( htmlentities($post->post_body, ENT_QUOTES) ) )?>
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
          <?
          if ( strlen($USER_LIST[$post->user_id]->signature) == 0 ) {
            //$USER_LIST[$post->user_id]->signature = generate_signature( $post->user_id );
          }
          ?>
          <div class="community_signature">
            <?=nl2br( community_bb_code( htmlentities($USER_LIST[$post->user_id]->signature, ENT_QUOTES) ) )?>
          </div>
        </td>
      </tr>
      </table>
      <?
    }

    ?>
    <!-- </table> -->
  </div>
  <?
}
?>