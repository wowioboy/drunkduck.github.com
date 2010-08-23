<style>
.content_body
{
  font: 11px Verdana, Arial, Helvetica, Geneva, sans-serif;
  color:#000000;
  background:#f8d689 url(http://images.drunkduck.com/site_gfx_new_v2/tan_page_bg.png) repeat-x 0 top;
  height:100%;
}

.content_body a {
  color: #00305b;
  font-weight:bold;
}
.content_body a:hover {
  color: #0067cc;
}
.content_body a:visited {
  color: #00305b;
}

.content_body img {
  border: 1px solid black;
}
</style>

<div class="content_body">

  <div style="background:url(<?=IMAGE_HOST?>/site_gfx_new_v2/page_title_bg.gif);height:40px;" align="left">
    <img src="<?=IMAGE_HOST?>/profile_gfx/profile_hdr.gif" style="margin-left:5px;margin-top:10px;float:left;border:0px;">
  </div>

  <?
  if ( !$_GET['u'] ) return;

  $U = db_escape_string($_GET['u']);

  $res = db_query("SELECT * FROM users WHERE username='".$U."'");
  if ( !$viewRow = db_fetch_object($res) ) return;
  db_free_result($res);


  ?><div align="left" style="margin:5px;"><a href="http://<?=USER_DOMAIN?>/<?=$viewRow->username?>">.: Back to <?=$viewRow->username?>'s profile</a></div><?

  $PER_PAGE = 70;

  $P = (int)$_GET['p']-1;
  if ( $P<0 ) $P=0;

  $res = db_query("SELECT COUNT(*) as total_friends FROM friends WHERE user_id='".$viewRow->user_id."'");
  $row = db_fetch_object($res);
  $TOTAL = $row->total_friends;

  $TOTAL_PAGES = ceil($TOTAL / $PER_PAGE);


  ?><div style="width:25%;"><b>Page <?=number_format($P+1)?>/<?=number_format($TOTAL_PAGES)?></b></div>

  <div align="center" style="width:25%;float:left;">
    <?
    if ( $P > 0 ) {
      ?><a href="<?=$_SERVER['PHP_SELF']?>?u=<?=$viewRow->username?>&p=<?=($P)?>">Prev <?=$PER_PAGE?></a><?
    }
    else {
      ?>Prev <?=$PER_PAGE?><?
    }
    ?>
  </div>
  <div align="center" style="width:25%;float:right;">
    <?
    if ( $P < $TOTAL_PAGES-1 ) {
      ?><a href="<?=$_SERVER['PHP_SELF']?>?u=<?=$viewRow->username?>&p=<?=($P+2)?>">Next <?=$PER_PAGE?></a><?
    }
    else {
      ?>Next <?=$PER_PAGE?><?
    }
    ?>
  </div><?


  $FRIENDS = array();
  $res = db_query("SELECT * FROM friends WHERE user_id='".$viewRow->user_id."' ORDER BY order_id DESC LIMIT ".($PER_PAGE*$P).",".$PER_PAGE);
  while( $row = db_fetch_object($res) ) {
      $FRIENDS[$row->friend_id] = $row->friend_id;
  }
  db_free_result($res);


  $res = db_query("SELECT * FROM users WHERE user_id IN ('".implode("','", $FRIENDS)."')");
  while( $row = db_fetch_object($res) ) {
    if ( isset($FRIENDS[$row->user_id]) ) {
      $FRIENDS[$row->user_id] = $row;
    }
  }


  ?>
  <br>
  <br>

  <table border="0" cellpadding="0" cellspacing="10" width="100%" >
    <tr>
      <td align="center" valign="top">
  <?
  $ct = -1;
  foreach($FRIENDS as $id=>$row)
  {
    if ( ++$ct%7 == 0 ) {
      ?></tr><tr><?
    }
    ?>
    <td align="center" valign="top">
      <a href='http://<?=USER_DOMAIN?>/<?=$row->username?>'><img src="<?=IMAGE_HOST?>/process/user_<?=$row->user_id?>.<?=$row->avatar_ext?>" width="50"><br><?=$row->username?></a>
    </td>
    <?
  }
  ?>
      </td>
    </tr>
  </table>

  <p>&nbsp;</p>

</div>