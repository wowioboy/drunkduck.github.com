<?
if ( $USER->flags & FLAG_IS_ADMIN ) {
  include_once('search_v2.inc.php');
  return;
}
?>
<div align="left">
  <h1>Browse and Search Users</h1>
</div>


<?
$SEARCH                         = array();
$SEARCH['searchTxt']                 = db_escape_string(trim( ( isset($_GET['searchTxt'])      ) ? $_GET['searchTxt']      : $_POST['searchTxt']       ));

?>

<form action="<?=$_SERVER['PHP_SELF']?>" method="get">


  <table border="0" cellpadding="5" cellspacing="5" width="740" class="sort">
    <tr>
      <td colspan="2" align="left">
        <a href="http://<?=DOMAIN?>/search.php">.: Go To "Search Comics"</a>
      </td>
    </tr>
    <tr>
      <td align="left" valign="middle" bgcolor="#001D37" style="color: white;"><strong>Search:</strong></td>
      <td  align="left" valign="middle" bgcolor="#001D37" style="color: white;">
        <input name="searchTxt" value="<?=$SEARCH['searchTxt']?>" type="text" size="72" style="width:100%;">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" bgcolor="#001D37" style="color: white;"><input name="submit" type="submit" value="Go!"></td>
    </tr>
  </table>
</form>


<hr>


<?

if ( strlen($SEARCH['searchTxt']) > 0 ) {
  $WHERE = "WHERE MATCH (users.username, users.about_self, users.signature) AGAINST ('".$SEARCH['searchTxt']."') OR MATCH (demographics.first_name, demographics.last_name) AGAINST ('".$SEARCH['searchTxt']."')";
}


$AMT_PER_PAGE = (int)$_GET['amtPerPage'];
if ( ($AMT_PER_PAGE != 10) && ($AMT_PER_PAGE != 25) && ($AMT_PER_PAGE != 50) )
{
  $AMT_PER_PAGE = 25;
}

$P = (int)$_GET['p']-1;
if ($P<0)$P=0;



if ( strlen($WHERE) > 0 )
{
  $res = db_query("SELECT COUNT(*) as total_results FROM users ".
         "LEFT JOIN demographics ".
         "USING (user_id) ".
         $WHERE);
}
else
{
  $res = db_query("SELECT COUNT(*) as total_results FROM users");
}

$row = db_fetch_object($res);
db_free_result($res);
$TOTAL = $row->total_results;
$TOTAL_PAGES = ceil($TOTAL / $AMT_PER_PAGE);


$RESULTS = array();

if ( strlen($WHERE) > 0 )
{
  $sql = "SELECT users.user_id, demographics.user_id, users.username, users.avatar_ext FROM users ".
         "LEFT JOIN demographics ".
         "USING (user_id) ".
         $WHERE.
         " LIMIT ".($P*$AMT_PER_PAGE).", ".$AMT_PER_PAGE;
}
else
{
  $sql = "SELECT user_id, user_id, username, avatar_ext FROM users LIMIT ".($P*$AMT_PER_PAGE).", ".$AMT_PER_PAGE;
}
$res = db_query($sql);
while($row = db_fetch_object($res) ) {
  $RESULTS[$row->user_id] = $row;
}
db_free_result($res);
?>



<table width="740" border="0" cellspacing="5" cellpadding="5" class="sort">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <tr>
    <td width="477" height="22" align="left" bgcolor="#001D37" style="color: white;">
      <strong>Result:</strong> <?=number_format($TOTAL)?> users were found.
      <?
      switch( $AMT_PER_PAGE )
      {
        case 10:
          ?>
            <strong><b>10 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=25">25 per page</a> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=50">50 per page</a>
          <?
        break;
        case 25:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=10">10 per page</a>  |
            <strong><b>25 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=50">50 per page</a>
          <?
        break;
        case 50:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=10">10 per page</a>  |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=25">25 per page</a> |
            <strong><b>50 per page</b></strong>
          <?
        break;
      }
      ?>
    </td>
    <td align="center" style="color: white;" bgcolor="#001D37">
      <div align="right">
        <?
          if ( $P > 0 ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$P?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Last <?=$AMT_PER_PAGE?></a><?
          }
        ?>

        Page <input name="p" value="<?=($P+1)?>" style="width: 30px; height:16px; font-size:10px;" type="text"> of <?=number_format($TOTAL_PAGES)?>
        <?
          if ( $P < ($TOTAL_PAGES-1) ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+2)?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Next <?=$AMT_PER_PAGE?></a><?
          }
        ?>
      </div>
    </td>
  </tr>
  <?
  foreach($SEARCH as $key=>$value)
  {
    if ( is_array($value) )
    {
      foreach($value as $value2)
      {
        ?><input type="hidden" name="<?=$key?>[]" value="<?=$value2?>"><?
      }
    }
    else
    {
      ?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
    }

  }
  ?>
  </form>
</table>





<table border="0" cellpadding="10" cellspacing="0" width="740" height="20" class="results">
  <tr bgcolor="#001D37">
    <?
    $ct = -1;
    foreach( $RESULTS as $row )
    {
      if ( ++$ct%5 == 0 ) {
        if ( $ct%2 == 0 ) {
          ?></TR><TR><?
        }
        else {
          ?></TR><TR bgcolor="#001D37"><?
        }
      }
      ?>
        <td align="center" valign="top" width="20%">
          <a href='http://<?=USER_DOMAIN?>/<?=$row->username?>'><img src="<?=IMAGE_HOST?>/process/user_<?=$row->user_id?>.<?=$row->avatar_ext?>" border="0"></a>
          <br>
          <?=$row->username?></a>
        </td>
      <?
    }
    ?>
  </tr>
</table>
















<table width="740" border="0" cellspacing="5" cellpadding="5" class="sort">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <tr>
    <td width="477" height="22" align="left" bgcolor="#001D37" style="color: white;">
      <strong>Result:</strong> <?=number_format($TOTAL)?> users were found.
      <?
      switch( $AMT_PER_PAGE )
      {
        case 10:
          ?>
            <strong><b>10 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=25">25 per page</a> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=50">50 per page</a>
          <?
        break;
        case 25:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=10">10 per page</a>  |
            <strong><b>25 per page</b></strong> |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=50">50 per page</a>
          <?
        break;
        case 50:
          ?>
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=10">10 per page</a>  |
            <a href="<?=$_SERVER['PHP_SELF']?>?<?=implode("&", $Q_STR)?>&amtPerPage=25">25 per page</a> |
            <strong><b>50 per page</b></strong>
          <?
        break;
      }
      ?>
    </td>
    <td align="center" style="color: white;" bgcolor="#001D37">
      <div align="right">
        <?
          if ( $P > 0 ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=$P?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Last <?=$AMT_PER_PAGE?></a><?
          }
        ?>

        Page <input name="p" value="<?=($P+1)?>" style="width: 30px; height:16px; font-size:10px;" type="text"> of <?=number_format($TOTAL_PAGES)?>
        <?
          if ( $P < ($TOTAL_PAGES-1) ) {
            ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+2)?>&<?=implode("&", $Q_STR)?>&amtPerPage=<?=$AMT_PER_PAGE?>">Next <?=$AMT_PER_PAGE?></a><?
          }
        ?>
      </div>
    </td>
  </tr>
  <?
  foreach($SEARCH as $key=>$value)
  {
    if ( is_array($value) )
    {
      foreach($value as $value2)
      {
        ?><input type="hidden" name="<?=$key?>[]" value="<?=$value2?>"><?
      }
    }
    else
    {
      ?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
    }

  }
  ?>
  </form>
</table>