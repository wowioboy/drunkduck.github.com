<?
if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);

$FOLDER_NAME = str_replace(' ', '_', $COMIC_ROW->comic_name);
?>

<SPAN CLASS='headline'><?=$COMIC_ROW->comic_name?></SPAN>

<DIV STYLE='WIDTH:600px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>Link:</DIV>
  
  <DIV ID='zipUpdate' ALIGN='CENTER'></DIV>
</DIV>

<script type="text/javascript">
function updateCharCt(feedback) {
  document.getElementById('zipUpdate').innerHTML = feedback;
}
if (1) {
  document.getElementById('zipUpdate').innerHTML = 'Now Zipping Files... Please Stand By...';
  ajaxCall('/xmlhttp/zip_comic.php?cid=<?=$CID?>', updateCharCt);
}
</script>