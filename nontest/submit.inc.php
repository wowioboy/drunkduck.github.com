<h1 class="style1" align="left">Send Submission</h1>

<?
$MAX_FILESIZE                           = '512000'; // 500k
$ACCEPTABLE_IMAGE_TYPES                 = array();
$ACCEPTABLE_IMAGE_TYPES['image/gif']    = 'gif';
$ACCEPTABLE_IMAGE_TYPES['image/jpeg']   = 'jpg';
$ACCEPTABLE_IMAGE_TYPES['image/pjpeg']  = 'jpg';
$ACCEPTABLE_IMAGE_TYPES['image/png']    = 'png';



if ( false && date("Ymd") < 20070126 ) {
  ?>Submissions haven't started yet! Please return at midnight, January 26.<?
  return;
}
else if ( false && date("Ymd") >= 20070203 ) {
  ?>Submissions have closed! Please return at midnight, February 7 to find out who won.<?
  return;
}

if ( isset($_GET['accepted']) )
{
  if ( $_GET['accepted'] == 0 ) {
    ?>There was an error in your file upload. Please check the file and try again.<?
    return;
  }
  else if ( $_GET['accepted'] == 1 ) {
    ?>Thank you for your submission. The winner will be chosen by our "Not Really Judges" and announced February 7th!<?
    return;
  }
}
else if ( isset($_FILES['file_upload']) )
{
  if ( $_FILES['file_upload']['size'] > $MAX_FILESIZE )  {
    ?>You file was way too large. Please submit something smaller.<?
    return;
  }

  $ext = getFileExt($_FILES['file_upload']['name']);
  if ( $ext == 'jpeg' ) $ext = 'jpg';

  if ( $ext != $ACCEPTABLE_IMAGE_TYPES[$_FILES['file_upload']['type']] ) {
    ?>Your file was corrupt. Please check it and try submitting again.<?
    return;
  }

  db_query("INSERT INTO nontest_entries (nontest_id, image_ext, user_id) VALUES ('1', '".db_escape_string($ext)."', '".$USER->user_id."')");
  $ID = db_get_insert_id();

  $new_filename = $ID.'.'.$ext;

  copy($_FILES['file_upload']['tmp_name'], WWW_ROOT.'/nontest/entries/1/'.$new_filename);
  header("Location: /nontest/submit.php?accepted=1");
  return;
}
?>

<TABLE BORDER="0"  CELLPADDING=5" CELLSPACING="3" WIDTH="500" HEIGHT="400" STYLE="BORDER:2px solid #c10547;" align="center">
  <TR>
    <TD bgcolor="#FFFFFF" width="500" height="168"><IMG SRC="formheader.jpg" ALIGN="LEFT" VALIGN="TOP"></TD>
  </tr>
  <tr>
    <TD BGCOLOR="#ffc1d7" align="center">

<table border="0" cellpadding="10" cellspacing="0">
  <form enctype="multipart/form-data" method="POST">
  <tr>
    <td align="left" style="color:black;">
      Acceptable image formats:
      <ul>
        <li>.gif</li>
        <li>.jpg</li>
        <li>.png</li>
      </ul>
      <br>
      Size Limit: 500kb
    </td>
  </tr>
  <tr>
    <td align="left" style="color:black;">
      <strong>Image:</strong>
      <input type="file" name="file_upload">
    </td>
  </tr>
  <tr>
    <td align="center">
      <input type="submit" value="Send!">
    </td>
  </tr>
  </form>
</table>
    </td>
  </tr>
</table>
