<?

ini_set("memory_limit", "128M");

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



if ( isset($_GET['homepage']) && $_GET['ck'] == md5('DD'.$USER->password) )
{
  if ( $_GET['homepage'] == 0 ) {
    $COMIC_ROW->flags &= ~FLAG_USE_HOMEPAGE;
    db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
  else {
    $COMIC_ROW->flags |= FLAG_USE_HOMEPAGE;
    db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
  }
}

if ( isset($_GET['reset']) && $_GET['ck'] == md5('DD'.$USER->password) )
{
  if ( $_GET['reset'] == 1 )
  {
    unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');
  }
  else
  {
    unlink(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/homepage.dd');
  }
  header("Location: http://".DOMAIN."/account/edit_template.php?cid=".$CID);
}

// process upload now?
if ( isset($_POST['pageTemplateCode']) )
{
  if ( strlen($_POST['pageTemplateCode']) > 500000 )
  {
    echo "<div align='center'>Page Template Code too long.</div>";
  }
  else
  {
    $_POST['templateCode'] = stripslashes($_POST['templateCode']);
    write_file($_POST['pageTemplateCode'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd');
    header("Location: http://".DOMAIN."/account/edit_template.php?cid=".$CID);
  }
}
if ( isset($_POST['homeTemplateCode']) )
{
  if ( strlen($_POST['homeTemplateCode']) > 500000 )
  {
    echo "<div align='center'>Home Page Template Code too long.</div>";
  }
  else
  {
    $_POST['homeTemplateCode'] = stripslashes($_POST['homeTemplateCode']);
    write_file($_POST['homeTemplateCode'], WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/homepage.dd');
    header("Location: http://".DOMAIN."/account/edit_template.php?cid=".$CID);
  }
}


if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd') ) {
  $PAGE_TEMPLATE = implode('', file(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/template.dd'));
}
else {
  $PAGE_TEMPLATE = implode('', file(WWW_ROOT.'/comics/resource_files/default_template.new.dd'));
}


if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/homepage.dd') ) {
  $HOME_TEMPLATE = implode('', file(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/pages/homepage.dd'));
}
else {
  $HOME_TEMPLATE = implode('', file(WWW_ROOT.'/comics/resource_files/default_homepage.dd'));
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
<h1 align="left">Alter Template</h2>


<?

  if ( isset($_POST['header_style']) )
  {
    db_query("UPDATE comics SET comic_caps_id='".(int)$_POST['header_style']."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
    $COMIC_ROW->comic_caps_id = (int)$_POST['header_style'];
  }
  ?>

  <div align="center"><b>Comic Header Style: <a href="http://<?=DOMAIN?>/toolbar_faq.php" style="font-size:9px;"><sup>(?)</sup></a></b></div>
  <div align="center">
    <table border="0" cellpadding="0" cellspacing="0">
    <form action="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>" method="POST">
    <?
    for($i=0; $i<15; $i++)
    {
      if ( $i%3 == 0 ) {
        ?></tr><tr><?
      }
      ?><td align="center"><input type="radio" name="header_style" value="<?=$i?>" <?=( ($COMIC_ROW->comic_caps_id==$i)?"CHECKED":"" )?>></td><td align="left"><img src="<?=IMAGE_HOST_SITE_GFX?>/comic_caps/previews/tb_<?=$i?>.gif"></td><?
    }
    ?>
    </tr>
    </table>
    <br>
    <input type="submit" value="Save Header Style">
    </form>
  </div>

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
        $PAGE_TEMPLATES[$TPL_TITLE] = $file;
      }
    }
    closedir($dp);

    ksort($PAGE_TEMPLATES, SORT_STRING);

    foreach($PAGE_TEMPLATES as $title=>$file) {
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
    <?
    if ( !($COMIC_ROW->flags & FLAG_USE_HOMEPAGE) )
    {
      ?>
      <br><br>
      <div align="center">
        <a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&homepage=1&ck=<?=md5('DD'.$USER->password)?>">Enable Home Page</A>
      </div>
      <?
    }
    else
    {
      ?>
      <DIV CLASS='header' ALIGN='CENTER'>Home Page Code:</DIV>
      <TEXTAREA NAME='homeTemplateCode' STYLE='WIDTH:600px;' ROWS='20' WRAP='OFF'><?=htmlentities($HOME_TEMPLATE, ENT_QUOTES)?></TEXTAREA>
      <DIV ALIGN='CENTER'>
        <INPUT TYPE='SUBMIT' VALUE='Send!'>
      </DIV>
      <DIV ALIGN='CENTER'>
        <A HREF='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&reset=2&ck=<?=md5('DD'.$USER->password)?>' onClick="return confirm('Are you SURE you want to reset all of your code?');">Reset</A>
      </DIV>

      <br>
      <div align="center">
        <a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&homepage=0&ck=<?=md5('DD'.$USER->password)?>">Disable Home Page</A>
      </div>
      <?
    }
    ?>

      <br><br>

    <DIV CLASS='header' ALIGN='CENTER'>Comic Page Code:</DIV>
    <TEXTAREA NAME='pageTemplateCode' STYLE='WIDTH:600px;' ROWS='20' WRAP='OFF'><?=htmlentities($PAGE_TEMPLATE, ENT_QUOTES)?></TEXTAREA>
    <DIV ALIGN='CENTER'>
      <INPUT TYPE='SUBMIT' VALUE='Send!'>
    </DIV>
    <DIV ALIGN='CENTER'>
      <A HREF='<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>&reset=1&ck=<?=md5('DD'.$USER->password)?>' onClick="return confirm('Are you SURE you want to reset all of your code?');">Reset</A>
    </DIV>

  </DIV>

  <INPUT TYPE='HIDDEN' NddesAME='newTemplate' VALUE='1'>
  </FORM>
  <?
}

?>