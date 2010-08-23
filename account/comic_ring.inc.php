<SPAN CLASS='headline'>Editing Comic Ring</SPAN>

<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='LEFT'>
<?

$res = db_query("SELECT * FROM comic_rings WHERE user_id='".$USER->user_id."'");
if ($row = db_fetch_object($res))
{
  ?><DIV CLASS='header' ALIGN='CENTER'><?=$row->ring_name?></DIV><?
  
  
}
else 
{
  ?>
  <DIV CLASS='header' ALIGN='CENTER'>Create new Ring</DIV>
  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='100%'>
    <TR>
     
    </TR>
  </TABLE>
  <?
}
?>

</DIV>