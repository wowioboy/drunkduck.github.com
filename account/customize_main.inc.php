<?
include(WWW_ROOT.'/index_modules.inc.php');

if ( (count($_POST['modsOn'])<=count($INDEX_MODULES)) && (count($_POST['modsOn'])>=1) ) {
  $NEW_MODS = array();
  foreach($_POST['modsOn'] as $modID) {
    if ( isset($INDEX_MODULES[$modID]) ) {
      $NEW_MODS[] = (int)$modID;
    }
  }
  
  
  if ( (count($NEW_MODS)<=count($INDEX_MODULES)) && (count($NEW_MODS)>=1) ) 
  {
    if ( implode(',', $NEW_MODS) != implode(',', $INDEX_MODS) ) {
      db_query("UPDATE index_modules SET mod_string='".implode(',', $NEW_MODS)."' WHERE user_id='".$USER->user_id."'");
      if ( db_rows_affected()==0 ) {
        db_query("INSERT INTO index_modules (user_id, mod_string) VALUES ('".$USER->user_id."', '".implode(',', $NEW_MODS)."')");
      }
    }
    my_setcookie('idx_mods', implode(',', $NEW_MODS));
    $INDEX_MODS = $NEW_MODS;
    
    header("Location: http://".DOMAIN."/account/customize_main.php");
    return;
  }
}

?>
<SPAN CLASS='headline'>Main Page Customization</SPAN>

<DIV STYLE='WIDTH:350px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Features on Main Page:</DIV>
  
  <TABLE BORDER='1' CELLPADDING='2' CELLSPACING='0' WIDTH='100%'>
    <TR>
      <TD ALIGN='CENTER'><B>ID</B></TD>
      <TD ALIGN='CENTER'><B>Description</B></TD>
      <TD ALIGN='CENTER'><B>Location</B></TD>
      <TD ALIGN='CENTER'><B>On/Off</B></TD>
    </TR>
  <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
  <?
  // Format:
  // $_COOKIE['idx'] = '#id,#id,#id=args|args';
  foreach( $INDEX_MODULES as $id=>$info )
  {
    ?>
    <TR>
      <TD ALIGN='CENTER'><?=number_format($id)?></TD>
      <TD ALIGN='LEFT'><?=$info['description']?></TD>
      <TD ALIGN='CENTER'><?=ucfirst($info['location'])?></TD>
      <TD ALIGN='CENTER'><INPUT TYPE='CHECKBOX' NAME='modsOn[]' VALUE='<?=$id?>' <?=( (in_array($id,$INDEX_MODS) )?"CHECKED":"" )?>></TD>
    </TR>
    <?
  }
  ?>
  <TR>
    <TD ALIGN='CENTER' COLSPAN='4'><INPUT TYPE='SUBMIT' VALUE='Change!'></TD>
  </TR>
  </FORM>
  </TABLE>
  
</DIV>