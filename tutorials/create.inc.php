<div align="left" class="header_title">
  Create Tutorial
</div>

<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
?>
<link href="tutorials.css" rel="stylesheet" type="text/css" />

<div >
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td align="left" width="160" valign="top">
          <?
          include('tutorial_side_nav.inc.php');
          ?>
        </td>
        <td align="center" valign="top" width="100%">
<form action="create_next.php" method="POST">
          <div style="margin:10px;" id="tutorial_body">
<?
if ( $_GET['edit_id'] )
{
  if ( $USER->flags & FLAG_IS_ADMIN ) {
    $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".(int)$_GET['edit_id']."'");
  }
  else {
    $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".(int)$_GET['edit_id']."' AND user_id='".$USER->user_id."'");
  }

  $EDIT_ROW = db_fetch_object($res);
}

if ( !$EDIT_ROW ) {
  $res = db_query("SELECT * FROM tutorials WHERE user_id='".$USER->user_id."' AND finalized='0'");
}

if ( !$EDIT_ROW && ($row = db_fetch_object($res)) )
{
  ?>You have a tutorial in the making now. Please click <a href="view.php?id=<?=$row->tutorial_id?>" id="tutorial">here</a> to finalize or delete it.<?
}
else
{
  ?>
  <p align="left">
    <b>Title</b><br>
    <input type="text" name="title" style="width:100%;" value="<?=htmlentities($EDIT_ROW->title, ENT_QUOTES)?>">
  </p>


  <p align="left">
    <b>Short Description</b><br>
    <input type="text" name="desc" style="width:100%;" maxlength="255" value="<?=htmlentities($EDIT_ROW->description, ENT_QUOTES)?>">
  </p>

  <p align="left">
    <b>Body</b><br>
    <script language="JavaScript">
      textareaName = 'body';
    </script>
    <script src="bbcode.js" language="JavaScript"></script>
    <textarea style="width:100%;" rows="15" id="body" name="body"><?=htmlentities($EDIT_ROW->body, ENT_QUOTES)?></textarea>
  </p>

  <?
  $TAGS = array();
  if ( $EDIT_ROW )
  {
    $res = db_query("SELECT * FROM tutorial_tags WHERE tutorial_id='".$EDIT_ROW->tutorial_id."'");
    while($row = db_fetch_object($res) )
    {
      $TAGS[] = $row->tag;
    }
  }
  ?>
  <p align="left">
    <b>Tags</b> (Separate tags with commas. Example: Inking, Lines, Line Weight)<br>
    <input type="text" name="tags" style="width:100%;" maxlength="255" value="<?=implode(", ", $TAGS)?>">
  </p>

  <p align="center">
    <?
    if ( $EDIT_ROW ) {
      ?>You will have the opportunity to attach files after you submit the text portion.<?
    }
    else {
      ?>You will have the opportunity to attach and delete files after you submit the text portion.<?
    }
    ?>
  </p>

  <p align="center">
    <input type="submit" value="Submit">
  </p>
  <?
}
?>
          </div>

        </td>
      </tr>
    </table>
    <?
    if ( $EDIT_ROW )
    {
      ?><input type="hidden" name="edit_id" value="<?=$EDIT_ROW->tutorial_id?>"><?
    }
    ?>
  </form>
</div>