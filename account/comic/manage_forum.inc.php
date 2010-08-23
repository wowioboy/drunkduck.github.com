<?
include(WWW_ROOT.'/community/community_functions.inc.php');
include(WWW_ROOT.'/community/community_data.inc.php');
include('header_edit_comic.inc.php');

$CID = ( isset($_GET['cid'])?(int)$_GET['cid']:(int)$_POST['cid'] );
if ( !$CID ) return;

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);

$res = db_query("SELECT COUNT(*) as total_categories FROM community_categories WHERE comic_id='".(int)$CID."'");
$row = db_fetch_object($res);
db_free_result($res);
if ( $row->total_categories == 0 ) {
  $COMIC_ROW->flags = $COMIC_ROW->flags & ~FLAG_HAS_FORUM;
}
else {
  $COMIC_ROW->flags = $COMIC_ROW->flags | FLAG_HAS_FORUM;
}
db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".(int)$CID."'");
// blog_forum_category_id

if (  isset($_POST['new_category_name']) )
{
  if ( strlen($_POST['new_category_name']) < 2 )
  {
    ?><div align="center"><b>Sorry, your new category name must be at least 2 characters long.</b></div><?
  }
  else
  {
    create_category(db_escape_string($_POST['new_category_name']), db_escape_string($_POST['new_category_desc']), $CID);
    header("Location: ".$_SERVER['PHP_SELF']."?cid=".$CID);
    return;
  }
}
else if ( isset($_POST['edit_cat']) )
{
  if ( isset($_POST['cat_delete']) )
  {
    // DELETE CATEGORY
    db_query("DELETE FROM community_categories WHERE category_id='".(int)$_POST['cat_delete']."' AND comic_id='".(int)$CID."'");
    if ( db_rows_affected() >= 1 )
    {
      $res = db_query("SELECT * FROM community_topics WHERE category_id='".(int)$_POST['cat_delete']."'");
      while($row = db_fetch_object($res) )
      {
        db_query("DELETE FROM community_posts WHERE topic_id='".$row->topic_id."'");
        db_query("DELETE FROM community_topics WHERE topic_id='".$row->topic_id."'");
      }

      if ( $COMIC_ROW->blog_forum_category_id == $_POST['cat_delete'] )
      {
        $COMIC_ROW->blog_forum_category_id = 0;
        db_query("UPDATE comics SET blog_forum_category_id='0' WHERE comic_id='".$CID."'");
      }
    }
  }
  else
  {
    if ( strlen($_POST['edit_category_name']) < 2 )
    {
      ?><div align="center"><b>Sorry, your category name must be at least 2 characters long.</b></div><?
    }
    else
    {
      db_query("UPDATE community_categories SET category_name='".db_escape_string($_POST['edit_category_name'])."', category_desc='".db_escape_string($_POST['edit_category_desc'])."' WHERE category_id='".(int)$_POST['edit_cat']."' AND comic_id='".$CID."'");
    }
  }
}
else if ( isset($_GET['blog']) && $_GET['ck'] == md5($USER->password.'ck') )
{
  $res = db_query("SELECT * FROM community_categories WHERE category_id='".(int)$_GET['blog']."' AND comic_id='".$CID."'");
  if ( $row = db_fetch_object($res))
  {
    db_query("UPDATE comics SET blog_forum_category_id='".$row->category_id."' WHERE comic_id='".$CID."'");
    $COMIC_ROW->blog_forum_category_id = $row->category_id;
  }
}

?>












<TABLE width="100%" BORDER='0' CELLPADDING='0' CELLSPACING='0'>
  <TR>
    <TD VALIGN='TOP'>

      <table border='0' cellpadding='0' cellspacing='0' width='100%'>
        <tr>
          <td colspan="5" align='LEFT' valign="top"><h3 align="left"><span style="float:left;">Comic Blog:</span><span style="float:right;"><a href="#" onClick="$('new_forum_form').style.display='';return false;"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/blog_create.gif" width="100" height="24" border="0" align="baseline"></a></span></h3></td>
        </tr>

        <tr>
          <td align="left" valign="top" class="community_hdr">Category</td>
          <td align="left" valign="top" class="community_hdr">Description</td>
          <td align="left" valign="top" class="community_hdr">Blog?</td>
          <td align="left" valign="top" class="community_hdr">Action</td>
          <td align="left" valign="top" class="community_hdr">Submit</td>
        </tr>

        <tr>
          <td colspan="5" id="new_forum_form" style="display:none;">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <table border="0" cellpadding="5" cellspacing="0" width="100%" class="userlist">
              <tr>
                <td height="20" valign="middle" align="center"><b>New Category</b></td>
              </tr>
              <tr>
                <td align="center">
                  Name
                  <br>
                  <input type="text" name="new_category_name" style="width:99%;">
                </td>
              </tr>
              <tr>
                <td align="center">
                  Description
                  <br>
                  <textarea name="new_category_desc" style="width:99%;" rows="5"></textarea>
                </td>
              </tr>
              <tr>
                <td valign="middle" align="center">
                  <input type="submit" value="Create!">
                </td>
              </tr>
            </table>
            <input type="hidden" name="cid" value="<?=$CID?>">
            </form>
          </td>
        </tr>

        <?
        $res = db_query("SELECT * FROM community_categories WHERE comic_id='".$CID."' ORDER BY category_id ASC");
        while($row = db_fetch_object($res) )
        {
          ?>
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
          <tr>
            <td align="left" valign="top" class="community_thrd">
              <input type="text" name="edit_category_name" value="<?=htmlentities($row->category_name, ENT_QUOTES)?>">
            </td>
            <td align="left" valign="top" class="community_thrd">
              <textarea name="edit_category_desc" style="width:99%;" rows="3"><?=htmlentities($row->category_desc, ENT_QUOTES)?></textarea>
            </td>
            <td align="left" valign="top" class="community_thrd">
              <?
              if ( $COMIC_ROW->blog_forum_category_id == $row->category_id ) {
                ?><font color='#00FF00'>Use as Blog</font><?
              }
              else {
                ?><a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&blog=<?=$row->category_id?>&ck=<?=md5($USER->password.'ck')?>">Use as Blog</a><?
              }
              ?>
            </td>
            <td align="left" valign="top" class="community_thrd">
              <input type="checkbox" name="cat_delete" value="<?=$row->category_id?>" onClick="return confirm('Are you SURE you want to delete this forum category? All topics and posts inside of it will be lost.');"> Delete
            </td>
            <td class="community_thrd">
              <input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0">
            </td>
          </tr>
          <input type="hidden" name="cid" value="<?=$CID?>">
          <input type="hidden" name="edit_cat" value="<?=$row->category_id?>">
          </form>
          <?
        }
        ?>

     </table>

  	</TD>
  </TR>
</TABLE>
<div align="center"><a href="http://<?=DOMAIN?>/community/?comic_id=<?=$COMIC_ROW->comic_id?>">Visit Forum</a></div>




























<?
include('footer_edit_comic.inc.php');
?>