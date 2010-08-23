<?
header("Location: http://".DOMAIN."/search.php");
die;
?>
<div align="left">
  <h1>Browse Comics</h1>
</div>
<hr>
<?
$AMT_PER_PAGE = 20;
$P = (int)$_GET['p']-1;
if ($P<0)$P=0;

if ( $_GET['tag'] )
{
  $C_IDS = array();
  $res = db_query("SELECT * FROM tags_by_comic WHERE tag='".db_escape_string($_GET['tag'])."' ORDER BY counter DESC");
  while($row = db_fetch_object($res) ) {
    $C_IDS[] = $row->comic_id;
  }
  db_free_result($res);

  $WHERE[] = "comic_id IN ('".implode("','", $C_IDS)."')";
}

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

$res = db_query("SELECT comic_id, comic_name, flags, description, category, total_pages FROM comics ".$WHERE." ORDER BY rating DESC LIMIT ".($P*$AMT_PER_PAGE).", ".$AMT_PER_PAGE);
?>



<DIV STYLE='WIDTH:800px;' ALIGN='CENTER' CLASS='container'>

<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>
<DIV ALIGN='center'>

  <table border="0" cellpadding="5" cellspacing="0" width='500'>
    <tr>
      <td align='left' width="33%" style='color:white;'>
        Type<br>
        <SELECT NAME='s' STYLE='WIDTH:150px;'>
          <OPTION VALUE='-1'>Any</OPTION>
          <?=getKeyValueSelect($COMIC_STYLES, $_GET['s'])?>
        </SELECT>
      </td>
      <td align='left' width="33%" style='color:white;'>
        Genre<br>
        <SELECT NAME='c' STYLE='WIDTH:150px;'>
          <OPTION VALUE='-1'>Any</OPTION>
          <?=getKeyValueSelect($COMIC_CATS, $_GET['c'])?>
        </SELECT>
      </td>
      <td align='left' width="33%" style='color:white;'>
        Tone<br>
        <SELECT NAME='sc' STYLE='WIDTH:150px;'>
          <OPTION VALUE='-1'>Any</OPTION>
          <?=getKeyValueSelect($COMIC_SUBCATS, $_GET['sc'])?>
        </SELECT>
      </td>
      <td valign="bottom" align='left' width='50'>
        <INPUT TYPE='SUBMIT' VALUE='Go!'>
      </td>
    </tr>
  </table>
</DIV>
</FORM>


<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='800'>
  <TR>
    <TD COLSPAN='5' ALIGN='left'>
      <hr />
      <table border="0" width="770">
        <form action='<?=$_SERVER['PHP_SELF']?>' method="get">
          <tr>
            <td width="300" height="22" align="left" style="color: white;"><h6><?=number_format($TOTAL)?> comics matched your selection criteria</h6></td>
            <td style="color: white;" align="left" width="50"><h6><?=(($P>0)?"<A HREF='".$_SERVER['PHP_SELF']."?p=".$P."&".implode('&', $PHP_APPEND)."'>Previous ".$AMT_PER_PAGE."</A>":"")?></h6></td>
            <td style="color: white;" align="center" width="200"><h6>Page <input name='p' type='text' value='<?=($P+1)?>' style='width:60px;'> of <?=number_format(ceil($TOTAL/$AMT_PER_PAGE))?></h6></td>
            <td style="color: white;" align="right" width="50"><h6><?=(($P*$AMT_PER_PAGE+$AMT_PER_PAGE<$TOTAL)?"<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+2)."&".implode('&', $PHP_APPEND)."'>Next ".$AMT_PER_PAGE."</A>":"")?></h6></td>
          </tr>
          <input name="s" value="" type="hidden">
          <input value="" name="c" type="hidden">
          <input value="" name="sc" type="hidden">
        </form>
      </table>
    </TD>
  </TR>
  <TR>
<?
$ct = -1;
while($row = db_fetch_object($res))
{
  if ( ++$ct%5 == 0 ) {
    ?></TR><TR><?
  }
  ?>
    <TD ALIGN='CENTER' WIDTH='20%' VALIGN='top'>
    <?

    if ( $row->total_pages > 0 )
    {
      echo get_current_thumbnail($row->comic_id, $row->comic_name);
    }

    ?>
    <br>
    <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <?=comic_name($row).((date("Ymd",$row->last_update)==YMD)?" *":"")?></TD>
  <?
}
?>
</TR>
</TABLE>
</DIV>