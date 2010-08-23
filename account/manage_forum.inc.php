<?
include(WWW_ROOT.'/community/community_functions.inc.php');
include(WWW_ROOT.'/community/community_data.inc.php');

$CID = ( isset($_GET['cid'])?(int)$_GET['cid']:(int)$_POST['cid'] );
if ( !$CID ) return;

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);

// blog_forum_category_id





?><h1 align="left">Manage Forums for "<?=$COMIC_ROW->comic_name?>"</h1><?


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


<div align="center"><a href="http://<?=DOMAIN?>/community/?comic_id=<?=$COMIC_ROW->comic_id?>">Visit Forum</a></div>
<br>

<table border="0" cellpadding="5" cellspacing="0" width="700" class="userlist" style="border:1px dashed white;">
    <tr>
      <td height="20" valign="middle" align="center" bgcolor="#000000"><b>Name</b></td>
      <td height="20" valign="middle" align="center" bgcolor="#000000"><b>Description</b></td>
      <td height="20" valign="middle" align="center" bgcolor="#000000" width="75"><b>Use as Blog?</b></td>
      <td height="20" valign="middle" align="center" bgcolor="#000000" width="75"><b>Delete?</b></td>
      <td height="20" valign="middle" align="center" bgcolor="#000000" width="75"><b>Change</b></td>
    </tr>
<?
$res = db_query("SELECT * FROM community_categories WHERE comic_id='".$CID."' ORDER BY category_id ASC");
while($row = db_fetch_object($res) )
{
  $bg  = '';
  if ( ++$ctr%2 == 0 ) {
    $bg = 'bgcolor="#3300CC"';
  }
  ?>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <tr>
    <td align="left" valign="middle" <?=$bg?>>
      <input type="text" name="edit_category_name" value="<?=htmlentities($row->category_name, ENT_QUOTES)?>">
    </td>
    <td align="left" valign="middle" <?=$bg?>>
      <textarea name="edit_category_desc" style="width:99%;" rows="3"><?=htmlentities($row->category_desc, ENT_QUOTES)?></textarea>
    </td>
    <td valign="middle" align="center" <?=$bg?>>
      <?
      if ( $COMIC_ROW->blog_forum_category_id == $row->category_id )
      {
        ?><font color='#00FF00'>Use as Blog</font><?
      }
      else
      {
        ?><a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&blog=<?=$row->category_id?>&ck=<?=md5($USER->password.'ck')?>">Use as Blog</a><?
      }
      ?>
    </td>
    <td valign="middle" align="center" <?=$bg?>>
      <input type="checkbox" name="cat_delete" value="<?=$row->category_id?>" onClick="return confirm('Are you SURE?');">
    </td>
    <td valign="middle" align="center" <?=$bg?>>
      <input type="submit" value="Edit">
    </td>
  </tr>
  <input type="hidden" name="cid" value="<?=$CID?>">
  <input type="hidden" name="edit_cat" value="<?=$row->category_id?>">
  </form>
  <?
}
?>
</table>

<br><br>

<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<table border="0" cellpadding="5" cellspacing="0" width="700" class="userlist" style="border:1px dashed white;">
  <tr>
    <td height="20" valign="middle" align="center" bgcolor="#000000"><b>New Category</b></td>
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