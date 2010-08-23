<?
$NAMES_PER_PAGE = 200;
$P = (int)$_GET['p']-1;
if ( $P<0 ) $P = 0;


if ( isset($_GET['addflag']) )
{
  $res = db_query("SELECT flags FROM comics WHERE comic_id='".(int)$_GET['comic_id']."'");
  if ( $row = db_fetch_object($res)) {
    db_query("UPDATE comics SET flags='".($row->flags | (int)$_GET['addflag'])."' WHERE comic_id='".(int)$_GET['comic_id']."'");
  }
}
else if ( isset($_GET['remflag']) )
{
  $res = db_query("SELECT flags FROM comics WHERE comic_id='".(int)$_GET['comic_id']."'");
  if ( $row = db_fetch_object($res)) {
    db_query("UPDATE comics SET flags='".($row->flags & ~((int)$_GET['remflag']))."' WHERE comic_id='".(int)$_GET['comic_id']."'");
  }
}


if ( isset($_GET['forcerating']) && (isset($RATINGS[$_GET['forcerating']]) || ($_GET['forcerating'] == 'K')) )
{
  $res = db_query("SELECT flags FROM comics WHERE comic_id='".(int)$_GET['comic_id']."'");
  if ( $row = db_fetch_object($res)) {

    if ( $_GET['forcerating'] == 'M' || $_GET['forcerating'] == 'A' ) {
      $row->flags = ($row->flags | FLAG_RATING_LOCKED);
    }
    db_query("UPDATE comics SET rating_symbol='".$_GET['forcerating']."', flags='".$row->flags."' WHERE comic_id='".(int)$_GET['comic_id']."'");
  }
}

$WHERE = '';
if ( $_GET['showflag'] ) {
  $WHERE = "WHERE (flags&".(int)$_GET['showflag'].")";
}
else if ( $_GET['comicsearch'] ) {
  $WHERE = "WHERE comic_name='".db_escape_string( trim($_GET['comicsearch']) )."' OR comic_name LIKE '%".db_escape_string( trim($_GET['comicsearch']) )."%'";
}

$res   = db_query("SELECT COUNT(*) as total_comics FROM comics $WHERE");
$row   = db_fetch_object($res);
$TOTAL = $row->total_comics;
?>

<SPAN CLASS='headline'>Comic List</SPAN>

<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>Search for IP Address</DIV>
<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>
    <INPUT TYPE="TEXT" NAME="ipSearch"> <INPUT TYPE='SUBMIT' VALUE='Search!'>

</FORM>
<BR>
<FORM ACTION='<?=$_SERVER['PHP_SELF']?>' METHOD='GET'>

  <DIV CLASS='header' ALIGN='CENTER'>Search for Comic</DIV>
  <INPUT TYPE="TEXT" NAME="comicsearch"> <INPUT TYPE='SUBMIT' VALUE='Search!'>
</DIV>
</FORM>

<DIV ALIGN='CENTER'>
  <A HREF='<?=$_SERVER['PHP_SELF']?>'>All comics</A>
  |
  <A HREF='<?=$_SERVER['PHP_SELF']?>?showflag=<?=FLAG_FROZEN?>'>Frozen comics</A>
</DIV>

<BR>

<DIV STYLE='WIDTH:900px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>
  <?
  if ( $_GET['showfrozen'] ) {
    echo "Frozen comics";
  }
  else {
    echo "All comics";
  }
  ?>
  </DIV>


  <TABLE BORDER='0' CELLPADDING='0' WIDTH='80%' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='25%'>
        <?
        if ( $P>0 ) {
          echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".$P."'>Previous ".$NAMES_PER_PAGE."</A>";
        }
        ?>
      </TD>
      <TD ALIGN='CENTER' WIDTH='50%'>
        p.<?=($P+1)?>/<?=number_format(ceil($TOTAL/$NAMES_PER_PAGE))?>
      </TD>
      <TD ALIGN='RIGHT' WIDTH='25%'>
        <?
        if ( ($P+2)<=ceil($TOTAL/$NAMES_PER_PAGE) ) {
          echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+2)."'>Next ".$NAMES_PER_PAGE."</A>";
        }
        ?>
      </TD>
  </TABLE>

  <TABLE BORDER='0' WIDTH='100%' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD ALIGN='LEFT'>
        <B>comic_name</B>
      </TD>
      <TD ALIGN='CENTER'>
        <B>Flags</B>
      <TD>
    </TR>
  <?php
  $res = db_query("SELECT * FROM comics $WHERE ORDER BY comic_name ASC LIMIT ".($P*$NAMES_PER_PAGE).",".$NAMES_PER_PAGE);
  while($row = db_fetch_object($res))
  {
    $BG = "#ffffff";
    if ( ++$ct%2 == 0 ) {
      $BG = "#efefef";
    }

    echo "<TR BGCOLOR='$BG' onMouseOver=\"this.style.background='#EDCEDC';\" onMouseOut=\"this.style.background='$BG';\">
            <TD ALIGN='LEFT'>".comic_name($row)."</TD>
            <TD>
            </TD>
            <TD ALIGN='CENTER' valign='middle'>";
    if ( $row->flags & FLAG_FROZEN ) {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&comic_id=".$row->comic_id."&remflag=".FLAG_FROZEN."'><FONT COLOR='red'>Frozen</FONT></A>";
    }
    else {
      echo "<A HREF='".$_SERVER['PHP_SELF']."?p=".($P+1)."&comic_id=".$row->comic_id."&addflag=".FLAG_FROZEN."'>Frozen</A>";
    }

    $SELECTED   = 'style=""';
    $UNSELECTED = 'style="filter:alpha(opacity=50);-moz-opacity:.50;opacity:.50;"';


      if ( 'K' == $row->rating_symbol ) {
        ?> | <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_K_sm.gif" border="0" <?=$SELECTED?>><?
      }
      else {
        ?> | <A HREF='<?=$_SERVER['PHP_SELF']?>?p=<?=($P+1)?>&comic_id=<?=$row->comic_id?>&forcerating=K'><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_K_sm.gif" border="0" <?=$UNSELECTED?> onClick="return confirm('Are you sure? You are suggesting that this is suitable for small children!');"></A><?
      }

    foreach($RATINGS as $symbol=>$description)
    {
      if ( $symbol == $row->rating_symbol ) {
        ?> | <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0" <?=$SELECTED?>><?
      }
      else {
        ?> | <A HREF='<?=$_SERVER['PHP_SELF']?>?p=<?=($P+1)?>&comic_id=<?=$row->comic_id?>&forcerating=<?=$symbol?>'><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0" <?=$UNSELECTED?> onClick="return confirm('Are you sure? If you set to an M or A rating this locks the rating!');"></A><?
      }
    }

    echo "</TD>
          </TR>";
  }
  ?>
  </TABLE>

</DIV>