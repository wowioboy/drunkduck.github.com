<?
$AMT_PER_PAGE = 20;
$P = (int)$_GET['p']-1;
if ($P<0)$P=0;

$WHERE[] = 'total_pages>0';

if ( isset($_GET['s']) && $COMIC_STYLES[$_GET['s']] ) {
  $PHP_APPEND[] = "s=".$_GET['s'];
  $WHERE[] = "comic_type='".$_GET['s']."'";
}
if ( isset($_GET['c']) && $COMIC_CATS[$_GET['c']] ) {
  $PHP_APPEND[] = "c=".$_GET['c'];
  $WHERE[] = "category  ='".$_GET['c']."'";
}
if ( isset($_GET['sc']) && $COMIC_SUBCATS[$_GET['sc']] ) {
  $PHP_APPEND[] = "sc=".$_GET['sc'];
  $WHERE[] = "subcategory='".$_GET['sc']."'";
}

$SEARCHING = false;
if ( isset($_POST['find']) && (strlen($_POST['find'])>=3) ) {
  $WHERE[] = "comic_name LIKE '%".db_escape_string($_POST['find'])."%' OR description LIKE '%".db_escape_string($_POST['find'])."%'";
  $SEARCHING = $_POST['find'];
  $PHP_APPEND[] = "find=".rawurlencode($SEARCHING);
}
else if ( isset($_GET['find']) && (strlen($_GET['find'])>3) ) {
  $WHERE[] = "comic_name LIKE '%".db_escape_string($_GET['find'])."%' OR description LIKE '%".db_escape_string($_GET['find'])."%'";
  $SEARCHING = $_GET['find'];
  $PHP_APPEND[] = "find=".rawurlencode($SEARCHING);
}

if ( count($WHERE) ) {
  $WHERE = "WHERE ".implode(" AND ", $WHERE);
}

$res = db_query("SELECT COUNT(*) as total FROM comics ".$WHERE);
$row = db_fetch_object($res);
db_free_result($res);
$TOTAL = $row->total;

$res = db_query("SELECT comic_name, flags, description, total_pages FROM comics ".$WHERE." ORDER BY rating DESC LIMIT ".($P*$AMT_PER_PAGE).", ".$AMT_PER_PAGE);
?>



<DIV STYLE='WIDTH:800px;' ALIGN='CENTER' CLASS='container'>

<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>
<DIV ALIGN='CENTER'>
<SELECT NAME='s' STYLE='WIDTH:150px;'>
<OPTION VALUE='-1'>Any</OPTION>
<?=getKeyValueSelect($COMIC_STYLES, $_GET['s'])?>
</SELECT>

<SELECT NAME='c' STYLE='WIDTH:150px;'>
<OPTION VALUE='-1'>Any</OPTION>
<?=getKeyValueSelect($COMIC_CATS, $_GET['c'])?>
</SELECT>

<SELECT NAME='sc' STYLE='WIDTH:150px;'>
<OPTION VALUE='-1'>Any</OPTION>
<?=getKeyValueSelect($COMIC_SUBCATS, $_GET['sc'])?>
</SELECT>

<INPUT TYPE='SUBMIT' VALUE='Go!'>
</DIV>
</FORM>


<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='100%'>
  <TR>
    <TD ALIGN='LEFT' WIDTH='200'><B>Title</B></TD>
    <TD ALIGN='LEFT' WIDTH='500'><B>Description</B></TD>
    <TD ALIGN='CENTER' WIDTH='100'><B>Pages</B></TD>
  </TR>
  <TR>
    <TD COLSPAN='3' BGCOLOR='#faa74a' STYLE='HEIGHT:3px;'></TD>
  </TR>
  <TR>
    <TD COLSPAN='3' ALIGN='CENTER'>
      <TABLE BORDER='0' WIDTH='300'>
        <TR>
          <TD ALIGN='LEFT' WIDTH='100'><?=(($P>0)?"<A HREF='".$_SERVER['PHP_SELF']."?p=".$P."&".implode('&', $PHP_APPEND)."'>Previous ".$AMT_PER_PAGE."</A>":"")?></TD>
          <TD ALIGN='CENTER' WIDTH='100'>p.<?=($P+1)?>/<?=number_format(ceil($TOTAL/$AMT_PER_PAGE))?></TD>
          <TD ALIGN='RIGHT' WIDTH='100'><?=(($P*$AMT_PER_PAGE+$AMT_PER_PAGE<$TOTAL)?"<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+2)."&".implode('&', $PHP_APPEND)."'>Next ".$AMT_PER_PAGE."</A>":"")?></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
<?
while($row = db_fetch_object($res))
{
  if ( $SEARCHING ) {
    $row->description = eregi_replace( "($SEARCHING)", "<FONT COLOR='#FF0000'>\\1</FONT>", $row->description);
  }
  $BG = '';
  if ( ++$ct%2 == 0 ) $BG = "BGCOLOR='#ECECEC'";
  ?>
  <TR <?=$BG?>>
    <TD ALIGN='LEFT' WIDTH='200'><?=comic_name($row).((date("Ymd",$row->last_update)==YMD)?" *":"")?></TD>
    <TD ALIGN='LEFT' WIDTH='500'><?=$row->description?></TD>
    <TD ALIGN='CENTER' WIDTH='100'><?=number_format($row->total_pages)?></TD>
  </TR>
  <?
}
?>
</TABLE>
</DIV>