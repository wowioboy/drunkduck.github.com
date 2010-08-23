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

  <div align="left" style="margin:5px;"><a href="http://<?=USER_DOMAIN?>/<?=$USER->username?>">.: Back to your profile</a></div>

  <?

  if (isset($_GET['top']) )
  {
    $ct = 12;
    $res = db_query("SELECT * FROM friends WHERE user_id='".$USER->user_id."' AND order_id>0 ORDER BY order_id DESC");
    while( $row = db_fetch_object($res) ) {
      db_query("UPDATE friends SET order_id='".$ct."' WHERE user_id='".$USER->user_id."' AND friend_id='".$row->friend_id."'");
      --$ct;
    }
    db_free_result($res);

    db_query("UPDATE friends SET order_id=order_id-1 WHERE user_id='".$USER->user_id."' AND order_id>0");
    db_query("UPDATE friends SET order_id=12 WHERE user_id='".$USER->user_id."' AND friend_id='".(int)$_GET['top']."'");
  }
  else if ( isset($_GET['offtop']) )
  {
    db_query("UPDATE friends SET order_id=0 WHERE user_id='".$USER->user_id."' AND friend_id='".(int)$_GET['offtop']."'");
    $ct = 12;
    $res = db_query("SELECT * FROM friends WHERE user_id='".$USER->user_id."' AND order_id>0 ORDER BY order_id DESC");
    while( $row = db_fetch_object($res) ) {
      db_query("UPDATE friends SET order_id='".$ct."' WHERE user_id='".$USER->user_id."' AND friend_id='".$row->friend_id."'");
      --$ct;
    }
    db_free_result($res);
  }
  else if ( isset($_GET['del']) ) {
    db_query("DELETE FROM friends WHERE user_id='".$USER->user_id."' AND friend_id='".(int)$_GET['del']."'");
  }

  $PER_PAGE = 35;

  $P = (int)$_GET['p']-1;
  if ( $P<0 ) $P=0;

  $res = db_query("SELECT COUNT(*) as total_friends FROM friends WHERE user_id='".$USER->user_id."'");
  $row = db_fetch_object($res);
  $TOTAL = $row->total_friends;

  $TOTAL_PAGES = ceil($TOTAL / $PER_PAGE);


  ?><div style="width:25%;"><b>Page <?=number_format($P+1)?>/<?=number_format($TOTAL_PAGES)?></b></div>

  <div align="center" style="width:25%;float:left;">
    <?
    if ( $P > 0 ) {
      ?><a href="<?=$_SERVER['PHP_SELF']?>?u=<?=$USER->username?>&p=<?=($P)?>">Prev <?=$PER_PAGE?></a><?
    }
    else {
      ?>Prev <?=$PER_PAGE?><?
    }
    ?>
  </div>
  <div align="center" style="width:25%;float:right;">
    <?
    if ( $P < $TOTAL_PAGES-1 ) {
      ?><a href="<?=$_SERVER['PHP_SELF']?>?u=<?=$USER->username?>&p=<?=($P+2)?>">Next <?=$PER_PAGE?></a><?
    }
    else {
      ?>Next <?=$PER_PAGE?><?
    }
    ?>
  </div><?


  $TOP_FRIENDS = array();
  $res = db_query("SELECT * FROM friends WHERE user_id='".$USER->user_id."' AND order_id>0 ORDER BY order_id DESC");
  while( $row = db_fetch_object($res) ) {
    $TOP_FRIENDS[$row->friend_id] = $row->friend_id;
  }

  $FRIENDS = array();
  $res = db_query("SELECT * FROM friends WHERE user_id='".$USER->user_id."' AND friend_id NOT IN ('".implode("','", $TOP_FRIENDS)."') ORDER BY order_id DESC LIMIT ".($PER_PAGE*$P).",".$PER_PAGE);
  while( $row = db_fetch_object($res) ) {
    if ( !isset($TOP_FRIENDS[$row->friend_id]) ) {
      $FRIENDS[$row->friend_id] = $row->friend_id;
    }
  }
  db_free_result($res);


  $res = db_query("SELECT * FROM users WHERE user_id IN ('".implode("','", $FRIENDS)."') OR user_id IN ('".implode("','", $TOP_FRIENDS)."')");
  while( $row = db_fetch_object($res) ) {
    if ( isset($FRIENDS[$row->user_id]) ) {
      $FRIENDS[$row->user_id] = $row;
    }
    else {
      $TOP_FRIENDS[$row->user_id] = $row;
    }
  }


  ?>
  <br>
  <br>
  <hr>
  <div style="font-weight:bold;font-size:18px;">Top Friends</div>
  <table border="0" cellpadding="0" cellspacing="10" width="100%" >
    <tr>
      <td align="center" valign="top">
  <?
  $ct = -1;
  foreach($TOP_FRIENDS as $id=>$row)
  {
    if ( ++$ct%4 == 0 ) {
      ?></tr><tr><?
    }
    ?>
    <td align="center" valign="top">
      <div align="center">
        <?
        if ( $row->order_id != 12 ) {
          ?><a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+1)?>&top=<?=$row->user_id?>"><img src="<?=IMAGE_HOST?>/profile_gfx/up.png" style="border:0px;" alt="Move to Top" Title="Move to Top"></a><?
        }
        ?>
        <a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+1)?>&offtop=<?=$row->user_id?>"><img src="<?=IMAGE_HOST?>/profile_gfx/down.png" style="border:0px;" alt="Remove from Top" Title="Remove from Top"></a>
      </div>
      <a href='http://<?=USER_DOMAIN?>/<?=$row->username?>'><img src="<?=IMAGE_HOST?>/process/user_<?=$row->user_id?>.<?=$row->avatar_ext?>" width="80"><br><?=$row->username?></a>
    </td>
    <?
  }
  ?>
      </td>
    </tr>
  </table>

  <hr>

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
      <div align="center">
        <a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+1)?>&top=<?=$row->user_id?>"><img src="<?=IMAGE_HOST?>/profile_gfx/up.png" style="border:0px;" alt="Move to Top" Title="Move to Top"></a>
        <a href="<?=$_SERVER['PHP_SELF']?>?p=<?=($P+1)?>&del=<?=$row->user_id?>" onClick="return confirm('Are you SURE you want to remove this friend?');"><img src="<?=IMAGE_HOST?>/profile_gfx/delete.png" style="border:0px;"></a>
      </div>
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