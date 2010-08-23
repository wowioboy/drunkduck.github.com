<?
if ( $USER->username == 'Volte6' ) {
  include('edit_template_v2.inc.php');
  return;
}
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

if ( isset($_POST['premadeTemplate']) && (($_POST['premadeTemplate']==0) || file_exists(WWW_ROOT.'/comics/resource_files/templates/'.$file)) ) {
  $COMIC_ROW->template = (int)$_POST['premadeTemplate'];
  db_query("UPDATE comics SET template='".(int)$_POST['premadeTemplate']."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
}

if ( isset($_GET['reset']) && $_GET['ck'] == md5('DD'.$USER->password) ) {
  unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');
  header("Location: http://".DOMAIN."/account/edit_template.php?cid=".$CID);
}

// process upload now?
if ( isset($_POST['templateCode']) )
{
  $_POST['templateCode'] = stripslashes($_POST['templateCode']);
  write_file($_POST['templateCode'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');
  header("Location: http://".DOMAIN."/account/edit_template.php?cid=".$CID);
}



if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd') ) {
  $TEMPLATE = implode('', file(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd'));
}
else {
  $TEMPLATE = implode('', file(WWW_ROOT.'/comics/resource_files/default_template.new.dd'));
}



?>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=HTTP_JAVASCRIPT?>/scriptaculous/effects.js"></SCRIPT>
<script type="text/javascript">
function showPreview( tID )
{
  $('preview').style.display = 'none';
  if ( Number(tID) == 0 ) return;
  $('imageDiv').innerHTML = "<IMG SRC='http://<?=DOMAIN?>/comics/resource_files/templates/"+tID+"/gfx/sample.gif' WIDTH='400' HEIGHT='450'>";
  new Effect.BlindDown('preview');
}
</script>
<SPAN CLASS='headline'>Alter Template</SPAN>


?>


<FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST'>
<DIV STYLE='WIDTH:450px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>Premade Templates:</DIV>
  <SELECT NAME='premadeTemplate' onChange="showPreview($F(this));">
    <OPTION VALUE='0' <?=(($COMIC_ROW->template==0)?"SELECTED":"")?>>None/Default/Own</OPTION>
  <?
    $dp = opendir(WWW_ROOT.'/comics/resource_files/templates');
    while ($file = readdir($dp) )
    {
      if ( $file != '.' && $file != '..' )
      {
        $TPL_TITLE = implode('', file(WWW_ROOT.'/comics/resource_files/templates/'.$file.'/name.txt'));
        $TEMPLATES[$TPL_TITLE] = $file;
      }
    }
    closedir($dp);

    ksort($TEMPLATES, SORT_STRING);

    foreach($TEMPLATES as $title=>$file) {
      echo "<OPTION VALUE='".$file."' ".(($COMIC_ROW->template==$file)?"SELECTED":"").">".$title."</OPTION>";
    }
  ?>
  </SELECT>
  <DIV ALIGN='CENTER'>
    <INPUT TYPE='SUBMIT' VALUE='Send!'>
  </DIV>
</DIV>
</FORM>

<DIV ID='preview' STYLE='display:none;WIDTH:450px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>Preview:</DIV>
  <DIV ID='imageDiv'> </DIV>
</DIV>

<?
if ( $COMIC_ROW->template == 0 )
{
  ?>
<FORM ACTION='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>' METHOD='POST'>
<DIV STYLE='WIDTH:600px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Template Code:</DIV>
  <TEXTAREA NAME='templateCode' STYLE='WIDTH:600px;' ROWS='20' WRAP='OFF'><?=htmlentities($TEMPLATE, ENT_QUOTES)?></TEXTAREA>
  <DIV ALIGN='CENTER'>
    <INPUT TYPE='SUBMIT' VALUE='Send!'>
  </DIV>
  <DIV ALIGN='CENTER'>
    <A HREF='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&reset=1&ck=<?=md5('DD'.$USER->password)?>'>Reset HTML</A>
  </DIV>
</DIV>
<INPUT TYPE='HIDDEN' NAME='newTemplate' VALUE='1'>
</FORM>
  <?
}
?>