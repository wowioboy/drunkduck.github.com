
<DIV ALIGN='CENTER'>
  <A HREF='<?=$_SERVER['PHP_SELF']?>?view=creators'><?=(($_GET['view']=='creators')?"<U>":"")?>Creators<?=(($_GET['view']=='creators')?"</U>":"")?></A> | <A HREF='<?=$_SERVER['PHP_SELF']?>'><?=(($_GET['view']!='creators')?"<U>":"")?>All<?=(($_GET['view']!='creators')?"</U>":"")?></A>
</DIV>
<?
$AGES = array();
$BIG = 0;
$res = db_query("SELECT * FROM demographics");
while($row = db_fetch_object($res) )
{
  if ( $_GET['view'] == 'creators' ) {
    if ( has_comic($row->user_id) )
    {
      $AGES[timestampToYears($row->dob_timestamp)]++;
    }
  }
  else {
    $AGES[timestampToYears($row->dob_timestamp)]++;
  }
}

$TOTAL = 0;
foreach($AGES as $age=>$amt)
{
  if ( $amt > $BIG ) $BIG = $amt;
  $TOTAL += $amt;
}

ksort($AGES, SORT_ASC);

echo "USERS: ".number_format($TOTAL)."<BR>";

echo "<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='0' WIDTH='650'>";
  ?><TR><TD ALIGN='CENTER' WIDTH='100'>Age</TD>
  <TD>Share</TD></TR>
  <?
foreach($AGES as $age=>$amt)
{
  ?><TR><TD ALIGN='CENTER' WIDTH='100'><?=$age?></TD>
  <TD align="left"><img src='<?=IMAGE_HOST?>/site_gfx_new/bar.gif' height='10' width='<?=floor(500*($amt/$BIG))?>'><?=number_format($amt)?></TD></TR>
  <?
}
echo "</TABLE>";


function has_comic($user_id)
{
  $res = db_query("SELECT COUNT(*) as total_ct FROM comics WHERE user_id='".$user_id."'");
  $row = db_fetch_object($res);
  if ( $row->total_ct > 0 ) return true;
  return false;
}
?>

<hr />

<?
$AGES = array();
$BIG = 0;
$res = db_query("SELECT * FROM demographics");
while($row = db_fetch_object($res) )
{
  $age = timestampToYears($row->dob_timestamp);

  if ( $age <= 12 ) {
    $AGES['12 & under'][$row->gender]++;
  }
  else if ( $age >= 13 && $age <= 16 ) {
    $AGES['13 to 16'][$row->gender]++;
  }
  else if ( $age >= 17 && $age <= 24 ) {
    $AGES['17 to 24'][$row->gender]++;
  }
  else if ( $age >= 25 && $age <= 34 ) {
    $AGES['25 to 34'][$row->gender]++;
  }
  else {
    $AGES['35+'][$row->gender]++;
  }


}


$TOTAL = 0;
$BIG   = 0;
foreach($AGES as $group=>$genders)
{
  $amt = $genders['m'] + $genders['f'];
  if ( $amt > $BIG ) $BIG = $amt;
  $TOTAL += $amt;
}

ksort($AGES, SORT_ASC);
?>

<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='650'>
  <TR>
    <TD ALIGN='CENTER' WIDTH='100'>Age</TD>
    <TD>Share</TD></TR>
    <?
    foreach($AGES as $group=>$genders)
    {
      ?>
      <TR>
        <TD ALIGN='right' WIDTH='100'>
          <?=$group?>
        </TD>
        <TD align="left">
          <img src='<?=IMAGE_HOST?>/site_gfx_new/bar.gif' height='10' width='<?=floor(500*($genders['f']/$BIG))?>'><?=number_format($genders['f'])?> (<?=floor($genders['f']/($genders['f']+$genders['m']) * 100)?>%) f
          <br>
          <img src='<?=IMAGE_HOST?>/site_gfx_new/bar.gif' height='10' width='<?=floor(500*($genders['m']/$BIG))?>'><?=number_format($genders['m'])?> (<?=floor($genders['m']/($genders['f']+$genders['m']) * 100)?>%) m
        </TD>
      </TR>
      <?
    }
    ?>
</TABLE>


<hr />

<?
$AGES = array();
$BIG = 0;
$res = db_query("SELECT * FROM demographics");
while($row = db_fetch_object($res) )
{
  $age = timestampToYears($row->dob_timestamp);

  if ( $age > 7 && $age < 14 ) {
    $AGES['8-13'][$row->gender]++;
  }
  else {
    $AGES['Other'][$row->gender]++;
  }


}


$TOTAL = 0;
$BIG   = 0;
foreach($AGES as $group=>$genders)
{
  $amt = $genders['m'] + $genders['f'];
  if ( $amt > $BIG ) $BIG = $amt;
  $TOTAL += $amt;
}

ksort($AGES, SORT_ASC);
?>

<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='650'>
  <TR>
    <TD ALIGN='CENTER' WIDTH='100'>Age</TD>
    <TD>Share</TD></TR>
    <?
    foreach($AGES as $group=>$genders)
    {
      ?>
      <TR>
        <TD ALIGN='right' WIDTH='100'>
          <?=$group?>
        </TD>
        <TD align="left">
          <img src='<?=IMAGE_HOST?>/site_gfx_new/bar.gif' height='10' width='<?=floor(500*($genders['f']/$BIG))?>'><?=number_format($genders['f'])?> (<?=floor($genders['f']/($genders['f']+$genders['m']) * 100)?>%) f
          <br>
          <img src='<?=IMAGE_HOST?>/site_gfx_new/bar.gif' height='10' width='<?=floor(500*($genders['m']/$BIG))?>'><?=number_format($genders['m'])?> (<?=floor($genders['m']/($genders['f']+$genders['m']) * 100)?>%) m
        </TD>
      </TR>
      <?
    }
    ?>
</TABLE>




<hr/>


<?
$AGES = array();
$BIG = 0;
$res = db_query("SELECT * FROM demographics");
while($row = db_fetch_object($res) )
{
  $age = timestampToYears($row->dob_timestamp);

  if ( $age <= 12 && $age > 3 ) {
    $AGES['12 & under']++;
  }
  else if ( $age >= 13 && $age <= 16 ) {
    $AGES['13 to 16']++;
  }
  else if ( $age >= 17 && $age <= 24 ) {
    $AGES['17 to 24']++;
  }
  else if ( $age >= 25 && $age <= 34 ) {
    $AGES['25 to 34']++;
  }
  else if ( $age < 50 ) {
    $AGES['35+']++;
  }


}


$TOTAL = 0;
$BIG   = 0;
foreach($AGES as $group=>$amt)
{
  $TOTAL += $amt;
}

ksort($AGES, SORT_ASC);
?>

<TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='650'>
  <TR>
    <TD ALIGN='CENTER' WIDTH='100'>Age</TD>
    <TD>Share</TD></TR>
    <?
    foreach($AGES as $group=>$ct)
    {
      ?>
      <TR>
        <TD ALIGN='right' WIDTH='100'>
          <?=$group?>
        </TD>
        <TD align="left">
          <img src='<?=IMAGE_HOST?>/site_gfx_new/bar.gif' height='10' width='<?=floor(500*($ct/$TOTAL))?>'><?=number_format($ct)?> ( <?=($ct/$TOTAL)?>  )
        </TD>
      </TR>
      <?
    }
    ?>
</TABLE>