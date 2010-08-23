<?
if ( is_uploaded_file($_FILES['avatar']['tmp_name']) )
{
  $ext = getExt($_FILES['avatar']['name']);

  if ( !in_array($ext, $ALLOWED_UPLOADS) ) {
    echo "<DIV ALIGN='CENTER' CLASS='microalert'>The file extension: ".$ext." is not supported.</DIV>";
    return;
  }

//  list($width, $height, $type, $attr) = getimagesize("img/flag.jpg");

  $INFO = getimagesize($_FILES['avatar']['tmp_name']);

  if ( $INFO[0] > 100 || $INFO[1] > 100 )
  {
    echo "<DIV ALIGN='CENTER' CLASS='microalert'>Please keep the avatar images dimensions under 100x100.</DIV>";
    return;
  }
  if ( $USER->avatar_ext ) {
    unlink(WWW_ROOT.'/gfx/avatars/avatar_'.$USER->user_id.'.'.$USER->avatar_ext);
  }
  copy($_FILES['avatar']['tmp_name'], WWW_ROOT.'/gfx/avatars/avatar_'.$USER->user_id.".".$ext);
  db_query("UPDATE users SET avatar_ext='".$ext."' WHERE user_id='".$USER->user_id."'");
}
header("Location: http://".DOMAIN."/account/overview/");
?>