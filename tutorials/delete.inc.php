<?
include_once(WWW_ROOT.'/tutorials/tutorials_tracking.inc.php');
?>
<?
include('tutorial_data.inc.php');

$TUTORIAL_ID = (int)$_POST['tutorial_id'];
$CK          = $_POST['ck'];

if ( $CK != md5($TUTORIAL_ID.'salt') ) return;

if ( $USER->flags & FLAG_IS_ADMIN ) {
  $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".$TUTORIAL_ID."'");
}
else {
  $res = db_query("SELECT * FROM tutorials WHERE tutorial_id='".$TUTORIAL_ID."' AND user_id='".$USER->user_id."'");
}

if ( $row = db_fetch_object($res) )
{
  delete_tutorial($TUTORIAL_ID);
  header("Location: index.php");
}
?>