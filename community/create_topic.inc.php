<?
include('community_header.inc.php');



if ( $COMIC_ROW ) {
  $res = db_query("SELECT category_id, category_name FROM community_categories WHERE category_id='".(int)$_GET['cid']."' AND comic_id='".$COMIC_ROW->comic_id."'");
}
else {
  $res = db_query("SELECT category_id, category_name FROM community_categories WHERE category_id='".(int)$_GET['cid']."'");
}

$CAT_VIEWING_ROW = db_fetch_object($res);
db_free_result($res);

if ( !$CAT_VIEWING_ROW ) {
  echo '<div align="center">Invalid Request.</div>';
}


if ( $COMIC_ROW && ($COMIC_ROW->blog_forum_category_id == $CAT_VIEWING_ROW->category_id) )
{
  if ( ($USER->user_id != $COMIC_ROW->user_id) && ($USER->user_id != $COMIC_ROW->secondary_author) )
  {
    echo '<div align="center">Invalid Request.</div>';
    return;
  }
}

?>

<div class="community_div" align="center">

  <div align="left" class="community_general_container">
    &raquo; <a href="index.php?<?=passables_query_string()?>">View Categories</a>
    &raquo; <a href="view_category.php?<?=passables_query_string()?>"><?=htmlentities($CAT_VIEWING_ROW->category_name, ENT_QUOTES)?></a>
    &raquo; Create New Topic
  </div>

  <form action="process_topic.php" method="POST">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">


      <tr>
        <td align="left" class='community_hdr' colspan="2">
            Create New Topic
        </td>
      </tr>

      <tr>
        <td align="left" colspan="2">
          Name
          <br>
          <input type="text" name="topic_name" style="width:99%">
        </td>
      </tr>

      <tr>
        <td align="left" colspan="2">
          Body
          <br>
          <script language="JavaScript">
              textareaName = 'topic_txt';
          </script>
          <script src="bbcode.js" language="JavaScript"></script>
          <textarea id="topic_txt" name="topic_txt" rows="20" style="width:99%"></textarea>
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
        if ( ($USER->flags & FLAG_IS_ADMIN) || ($USER->flags & FLAG_IS_MOD) ) {
          ?><input id="sticky" type="checkbox" name="sticky" <?=(($row->sticky)?"CHECKED":"")?> value="1"> <label for="sticky">Sticky</label><?
          ?><input id="important" type="checkbox" name="important" <?=(($row->flags & FORUM_FLAG_IMPORTANT)?"CHECKED":"")?> value="1"><label for="important">Important!</label><?
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

</div>