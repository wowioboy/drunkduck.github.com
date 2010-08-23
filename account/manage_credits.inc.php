<?
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


?><h1 align="left">Manage Credits for "<?=$COMIC_ROW->comic_name?>"</h1><?



if ( $_GET['del'] )
{
  db_query("DELETE FROM credits WHERE comic_id='".$CID." AND credit_id='".(int)$_POST['del']."'");
}

if ( $_POST['edit_credit_id'] == 'new' )
{
  db_query("INSERT INTO credits (comic_id, credit_name, credit_value) VALUES ('".$CID."', '".db_escape_string($_POST['credit_name'])."', '".db_escape_string($_POST['credit_value'])."')");
}
else if ( $_POST['edit_credit_id'] > 0 )
{
  db_query("UPDATE credits SET credit_name='".db_escape_string($_POST['credit_name'])."', credit_value='".db_escape_string($_POST['credit_value'])."' WHERE comic_id='".$CID."' AND credit_id='".(int)$_POST['edit_credit_id']."'");
}

?>
<table border="0" cellpadding="2" cellspacing="3" width="600">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <tr><td align="left">Credit Name:<input type="text" name="credit_name" value="" style="width:100%;"></td><td align="left">Person Name:<input type="text" name="credit_value" value="" style="width:100%;"></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="Add!"></td></tr>
    <input type="hidden" name="edit_credit_id" value="new">
    <input type="hidden" name="cid" value="<?=$CID?>">
  </form>
</table>


<table border="0" cellpadding="2" cellspacing="3" width="600"><?

$res = db_query("SELECT * FROM credits WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY credit_name ASC");
while($row = db_fetch_object($res))
{
  ?>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <tr><td align="left" width="40%"><input type="text" name="credit_name" value="<?=htmlentities($row->credit_name, ENT_QUOTES)?>" style="width:100%;"></td><td align="left" width="40%"><input type="text" name="credit_value" value="<?=htmlentities($row->credit_value, ENT_QUOTES)?>" style="width:100%;"></td><td align="center" width="20%"><input type="submit" value="Edit!"> <a href="<?=$_SERVER['PHP_SELF']?>?del=<?=$row->credit_id?>&cid=<?=$CID?>">Delete</a></td></tr>
    <input type="hidden" name="edit_credit_id" value="<?=$row->credit_id?>">
    <input type="hidden" name="cid" value="<?=$CID?>">
  </form>
  <?
}
?>
</table>