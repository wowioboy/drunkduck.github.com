<?
include(WWW_ROOT.'/banned_ip_data.inc.php');

if ( count($_POST['removeIps']) ) 
{
  ob_start();
  echo "<"."?php\n";

  echo "  \$BANNED_IPS = array();\n";
  
  foreach($BANNED_IPS as $ip=>$date) 
  {
    if ( !in_array($ip, $_POST['removeIps']) )
    {
      echo "  \$BANNED_IPS['".$ip."'] = ".$date.";\n";
    }
  }
  
  echo "?".">";
  $DATA = ob_get_contents();
  
  write_file($DATA, WWW_ROOT.'/banned_ip_data.inc.php');
  unset($BANNED_IPS);
  include(WWW_ROOT.'/banned_ip_data.inc.php');
  ob_clean();
}
else if ( isset($_POST['ipToBan']) )
{
  ob_start();
  echo "<"."?php\n";

  echo "  \$BANNED_IPS = array();\n";
  
  foreach($BANNED_IPS as $ip=>$date) {
    echo "  \$BANNED_IPS['".$ip."'] = ".$date.";\n";
  }
  echo "  \$BANNED_IPS['".$_POST['ipToBan']."'] = ".time().";\n";
  
  echo "?".">";
  $DATA = ob_get_contents();
  
  write_file($DATA, WWW_ROOT.'/banned_ip_data.inc.php');
  unset($BANNED_IPS);
  include(WWW_ROOT.'/banned_ip_data.inc.php');
  ob_clean();
}
?>

<DIV STYLE='WIDTH:400px;' ALIGN='CENTER' CLASS='container'>
  <DIV CLASS='header' ALIGN='CENTER'>IP Ban:</DIV>
<BR>
<i><B>NOTE:</B> Only use this in the most extreme circumstances. You could end up banning all AOL users etc.</i>
<BR>
  <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
    <INPUT TYPE='TEXT' NAME='ipToBan'> <INPUT TYPE='SUBMIT' VALUE='BAN!'>
  </FORM>
  
  <TABLE BORDER='1' CELLPADDING='3' CELLSPACING='0' WIDTH='100%'>
    <TR>
      <TD ALIGN='LEFT'><B>IP Address</B></TD>
      <TD ALIGN='LEFT'><B>Date Banned</B></TD>
      <TD ALIGN='LEFT'><B>Remove</B></TD>
    </TR>
    
  <FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='POST'>
  <?
  foreach($BANNED_IPS as $ip=>$date)
  {
    ?>
    <TR>
      <TD ALIGN='CENTER'><?=$ip?></TD>
      <TD ALIGN='CENTER'><?=date("m-d-Y", $date)?></TD>
      <TD ALIGN='CENTER'><INPUT TYPE='CHECKBOX' NAME='removeIps[]' VALUE='<?=$ip?>'></TD>
    </TR>
    <?
  }
  ?>
    <TR>
      <TD ALIGN='CENTER' COLSPAN='3'><INPUT TYPE='SUBMIT' VALUE='Make it so.'></TD>
    </TR>
  </FORM>
  </TABLE>
</DIV>