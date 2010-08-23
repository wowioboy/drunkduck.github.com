<?
include('community_header.inc.php');

$res = db_query("SELECT topic_id, category_id, topic_name, flags FROM community_topics WHERE topic_id='".(int)$_GET['tid']."'");
$TOPIC_VIEWING_ROW = db_fetch_object($res);
db_free_result($res);

$res = db_query("SELECT category_name FROM community_categories WHERE category_id='".$TOPIC_VIEWING_ROW->category_id."'");
$CAT_VIEWING_ROW = db_fetch_object($res);
db_free_result($res);
?>

<div class="community_div" align="center">

  <div align="left" class="community_general_container">
    &raquo; <a href="index.php?<?=passables_query_string()?>">View Categories</a>
    &raquo; <a href="view_category.php?<?=passables_query_string()?>"><?=htmlentities($CAT_VIEWING_ROW->category_name, ENT_QUOTES)?></a>
    &raquo; <a href="view_topic.php?<?=passables_query_string()?>"><?=htmlentities($TOPIC_VIEWING_ROW->topic_name, ENT_QUOTES)?></a>
    &raquo; Edit Post<?=( ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED)?" *LOCKED*":"")?>
  </div>

<?

$CAN_MODERATE = false;
if ( $COMIC_ROW )
{
  // If they are the owner of the comic or an admin or it's their post
  if ( ($USER->user_id == $COMIC_ROW->user_id) || ($USER->user_id == $COMIC_ROW->secondary_author) ||
       ($USER->user_id == $post->user_id) || ($USER->flags & FLAG_IS_ADMIN) ||
       ($USER->flags & FLAG_IS_MOD) )
  {
    $CAN_MODERATE = true;
  }
}
else if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) )
{
  $CAN_MODERATE = true;
}



if ( !$CAN_MODERATE && ($TOPIC_VIEWING_ROW->flags & FORUM_FLAG_LOCKED) )
{
  ?><div align="center">This forum thread is LOCKED</div><?
}
else
{
  $res = db_query("SELECT * FROM community_posts WHERE post_id='".(int)$_GET['pid']."'");
  if ( $row = db_fetch_object($res) )
  {
    db_free_result($res);

    if ( $CAN_MODERATE || ($USER->user_id == $row->user_id) )
    {
      ?>
      <form action="process_edit.php" method="POST">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">

          <tr>
            <td align="left" colspan="2">
              <div class='community_hdr'>
                Edit
              </div>
            </td>
          </tr>

          <tr>
            <td align="left" colspan="2">
              Body
              <br>
              <script language="JavaScript">
                textareaName = 'edit_txt';
              </script>
              <script src="bbcode.js" language="JavaScript"></script>
              <textarea id="edit_txt" name="edit_txt" rows="20" style="width:99%"><?=htmlentities($row->post_body, ENT_QUOTES)?></textarea>
            </td>
          </tr>
          <tr>
            <td align="left" colspan="2">
              <a href="http://<?=DOMAIN?>/community/emotes.php" target="_blank">Emotes</a>
            </td>
          </tr>
          <tr>
            <td align="left">
          <?
          if ( $CAN_MODERATE )
          {
            $res = db_query("SELECT sticky, flags FROM community_topics WHERE topic_id='".$row->topic_id."'");
            $sRow = db_fetch_object($res);
            db_free_result($res);
            $STICKY = $sRow->sticky;
            $LOCKED = 0;

            $res = db_query("SELECT post_id FROM community_posts WHERE topic_id='".$row->topic_id."' ORDER BY post_id ASC LIMIT 1");
            $tRow = db_fetch_object($res);
            db_free_result($res);
            if ( $tRow->post_id == $row->post_id ) {
              ?><input id="sticky" type="checkbox" name="sticky" <?=(($STICKY)?"CHECKED":"")?> value="1"><label for="sticky">Sticky</label> <?
              ?><input id="locked" type="checkbox" name="locked" <?=(($sRow->flags & FORUM_FLAG_LOCKED)?"CHECKED":"")?> value="1"><label for="locked">Locked</label><?
              ?><input id="important" type="checkbox" name="important" <?=(($sRow->flags & FORUM_FLAG_IMPORTANT)?"CHECKED":"")?> value="1"><label for="important">Important!</label><?
            }
          }
          ?>
            </td>
            <td align="right">
              <input type="submit" value="Send!">
            </td>
          </tr>

        </table>
        <?=passables_hidden_field()?>
      </form>
      <?
    }

    if ( $CAN_MODERATE )
    {
      $res = db_query("SELECT post_id FROM community_posts WHERE topic_id='".$row->topic_id."' ORDER BY post_id ASC LIMIT 1");
      $tRow = db_fetch_object($res);
      db_free_result($res);
      if ( $tRow->post_id == $row->post_id )
      {
        ?>
        <form action="process_edit.php" method="POST">
          <select name="move_category_id">
            <?
            if ( $USER->flags & FLAG_IS_MOD ) {
              $res = db_query("SELECT * FROM community_categories WHERE comic_id='".(int)$COMIC_ROW->comic_id."' AND NOT (flags&".FORUM_FLAG_ADMIN_ONLY.")");
            }
            else {
              $res = db_query("SELECT * FROM community_categories WHERE comic_id='".(int)$COMIC_ROW->comic_id."' AND NOT (flags&".(FORUM_FLAG_MOD_ONLY|FORUM_FLAG_ADMIN_ONLY).")");
            }

            while($row = db_fetch_object($res))
            {
              ?><option value="<?=$row->category_id?>"><?=htmlspecialchars($row->category_name,ENT_QUOTES)?></option><?
            }
            ?>
          </select>
          <input type="submit" value="Move!">
          <?=passables_hidden_field()?>
        </form>
        <?
      }
    }
  }
  ?>
  </div>
  <?
}
?>