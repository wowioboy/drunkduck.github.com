<div align="center">
  <?
  if ( $_GET['a'] == 'new' ) {
    ?><b>New</b><?
  }
  else {
    ?><a href="<?=$_SERVER['PHP_SELF']?>?a=new">New</a><?
  }
  ?> | <?
  if ( $_GET['a'] == 'list' ) {
    ?><b>List</b><?
  }
  else {
    ?><a href="<?=$_SERVER['PHP_SELF']?>?a=list">List</a><?
  }
  ?>
</div>
<?
/*
$ct = 0;
$res = db_query("SELECT * FROM featured_comics WHERE approved='1' order by feature_id desc");
while($row = db_fetch_object($res) )
{
  $ts = mktime(0,0,0,date("m"),date("d")-$ct,date("Y"));
  $ct++;

  db_query("UPDATE featured_comics SET ymd_date_live='".date("Ymd", $ts)."' WHERE feature_id='".$row->feature_id."'");
}
*/

if ( $USER->flags & FLAG_EDITOR )
{
  if ( $_GET['approve'] )
  {
    db_query("UPDATE featured_comics SET approved='1' WHERE feature_id='".(int)$_GET['approve']."'");
  }
  else if ( $_GET['unapprove'] )
  {
    db_query("UPDATE featured_comics SET approved='0' WHERE feature_id='".(int)$_GET['unapprove']."'");
  }
  else if ( $_GET['del'] ) {
    db_query("DELETE FROM featured_comics WHERE feature_id='".(int)$_GET['del']."'");
  }
}

if ( ($_POST['comic_name'] || $_POST['edit_id']) && (strlen($_POST['feature_desc']) > 10) )
{
  if ( $_POST['edit_id'] )
  {
    $ID = (int)$_POST['edit_id'];
    if ( is_uploaded_file($_FILES['demo_image']['tmp_name']) )
    {
      copy($_FILES['demo_image']['tmp_name'], WWW_ROOT.'/gfx/tmp_img_cache/'.$_FILES['demo_image']['name']);
      error_reporting(E_ALL | E_ERROR);
      thumb( WWW_ROOT.'/gfx/tmp_img_cache/'.$_FILES['demo_image']['name'], WWW_ROOT.'/gfx/featured_comic_gfx/'.$ID.'.gif', 160, 220, false);
    }

    db_query("UPDATE featured_comics SET description='".db_escape_string($_POST['feature_desc'])."' WHERE feature_id='".$ID."'");
    header("Location: ".$_SERVER['PHP_SELF']."?a=list");
  }
  else
  {
    if ( is_uploaded_file($_FILES['demo_image']['tmp_name']) )
    {
      $res = db_query("SELECT * FROM comics WHERE comic_name='".db_escape_string($_POST['comic_name'])."'");
      if ( $row = db_fetch_object($res) ) {
        db_query("INSERT INTO featured_comics (ymd_date_live, comic_id, description, category) VALUES ('0', '".$row->comic_id."', '".db_escape_string($_POST['feature_desc'])."', '".$row->category."')");
      }
      db_free_result($res);

      $ID = db_get_insert_id();

      copy($_FILES['demo_image']['tmp_name'], WWW_ROOT.'/gfx/'.$_FILES['demo_image']['name']);
      thumb( WWW_ROOT.'/gfx/'.$_FILES['demo_image']['name'], WWW_ROOT.'/gfx/featured_comic_gfx/'.$ID.'.gif', 160, 220, true);
    }
    else
    {
      ?><div align="center">Image was not uploaded.</div><?
    }
  }
}







if ( $_GET['a'] == 'new' || $_GET['edit'] )
{
  if ( $_GET['edit'] )
  {
    $res      = db_query("SELECT * FROM featured_comics WHERE feature_id='".(int)$_GET['edit']."'");
    $EDIT_ROW = db_fetch_object($res);
    db_free_result($res);

    $res        = db_query("SELECT * FROM comics WHERE comic_id='".$EDIT_ROW->comic_id."'");
    $COMIC_ROW  = db_fetch_object($res);
    db_free_result($res);
  }
  ?>
  <script language="JavaScript">
    function findComic(input)
    {
      ajaxCall('/xmlhttp/find_comic.php?try='+input, comicFound, false);
    }
    function comicFound(resp)
    {
      $('foundcomics').innerHTML = resp;
    }
    function insertFindComic(user)
    {
      $('comic_name').value = user;
      $('foundcomics').innerHTML = '';
    }
  </script>

  <table border="0" cellpadding="3" cellspacing="0" width="600">

    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
    <tr>
      <td align="right" width="100" valign="top"><b>Comic Name</b></td>
      <td align="right" width="500" valign="top">
        <input type="text" name="comic_name" style="width:100%;" onKeyUp="findComic(this.value);" value="<?=$COMIC_ROW->comic_name?>" <?=( ($_GET['edit'])?"DISABLED":"")?>>
        <div align="left" id="foundcomics"></div>
      </td>
    </tr>

    <tr>
      <td align="right" width="100" valign="top"><b>Demo Image</b><br><div align="center" style="font-size:9px;">160x220</div></td>
      <td align="right" width="500"><input type="file" name="demo_image" style="width:100%"></td>
    </tr>

    <tr>
      <td align="right" width="100" valign="top"><b>Description</b></td>
      <td align="right" width="500"><textarea style="width:100%;" rows="10" name="feature_desc"><?=htmlentities($EDIT_ROW->description, ENT_QUOTES)?></textarea></td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <input type="submit" value="Submit This Comic!">
      </td>
    </tr>
    <?
    if ( $_GET['edit'] )
    {
      ?><input type="hidden" name="edit_id" value="<?=$_GET['edit']?>"><?
    }
    ?>
    </form>

  </table>
  <?
}
else
{
  ?><br><?
  $res = db_query("SELECT * FROM featured_comics ORDER BY feature_id DESC");
  while($row = db_fetch_object($res) )
  {
    $res2 = db_query("SELECT * FROM comics WHERE comic_id='".$row->comic_id."'");
    $COMIC_ROW = db_fetch_object($res2);
    db_free_result($res2);

    $url = 'http://'.DOMAIN.'/'.comicNameToFolder($COMIC_ROW->comic_name).'/';
    ?><table border="0" cellpadding="3" cellspacing="0" width="500">
      <tr <?=( !($row->approved)?"BGCOLOR='#cdcdcd'":"" )?>>
        <td width="160"><a href="<?=$url?>" target="_blank"><img src="<?=IMAGE_HOST?>/featured_comic_gfx/<?=$row->feature_id?>.gif" border="0"></a></td>
        <td width="100%" valign="top">
          <div align="center" style="font-weight:bold;font-size:22px;"><a href="<?=$url?>" target="_blank"><?=$COMIC_ROW->comic_name?></a></div>
          <div align="center" style="font-size:9px;">
          <?
          if ( $row->ymd_date_live == 0 ) {
            ?>Not Featured Yet<?
          }
          else {
            list($year, $month, $day) = sscanf($row->ymd_date_live, "%4d%2d%2d");
            echo("Featured on $month-$day-$year");
          }
          ?>
          </div>
          <br>
          <div align="left">
            <?=$row->description?>
          </div>
          <br>
          <div align="left"><a href="<?=$_SERVER['PHP_SELF']?>?edit=<?=$row->feature_id?>">Edit</a></div>
          <?
          if ( $USER->flags & FLAG_EDITOR )
          {
            if ( $row->approved == 0 ) {
              ?><div align="left"><a href="<?=$_SERVER['PHP_SELF']?>?approve=<?=$row->feature_id?>">Approve</a></div><?
            }
            else {
              ?><div align="left"><a href="<?=$_SERVER['PHP_SELF']?>?unapprove=<?=$row->feature_id?>">Unapprove</a></div><?
            }
            ?><div align="left"><a href="<?=$_SERVER['PHP_SELF']?>?del=<?=$row->feature_id?>" onClick="return confirm('Are you SURE you want to delete this entry?');">Delete</a></div><?
          }
          ?>
        </td>
      </tr>
    </table>
    <br>
    <?
  }
}
?>