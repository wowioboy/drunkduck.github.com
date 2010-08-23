<div style="height:24px;background:url(<?=$IMAGE_FOLDER?>/sub_title_hdr.gif);color:#ffffff;font-weight:bold;" align="left">
  <div style="padding-left:5px;padding-top:4px;"><?=$viewRow->username?>'s Friends (<?=number_format($GLOBALS['total_friends'])?>)</div>
</div>

<div style="background:#ffffff;margin-bottom:5px;" align="left">
  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <?
      if ( count($GLOBALS['FRIEND_DATA']->top_friends) == 0 ) {
        ?><td align="left">None.</td><?
      }

      $ct = -1;
      foreach( $GLOBALS['FRIEND_DATA']->top_friends as $row )
      {
        if ( ++$ct%4 == 0 ) {
          ?></tr><tr><?
        }
        ?>
          <td align="center" valign="top" width="25%">
            <a href='http://<?=USER_DOMAIN?>/<?=$row->username?>'><img src="<?=IMAGE_HOST?>/process/user_<?=$row->user_id?>.<?=$row->avatar_ext?>" width="50"></a>
            <br>
            <?=username($row->username)?>
          </td>
        <?
      }
      db_free_result($res);
      ?>
    </tr>
  </table>
  <div align="right"><a href="http://<?=USER_DOMAIN?>/friends.php?u=<?=$viewRow->username?>">See all <?=$viewRow->username?>'s friends.</a></div>
  <?
  if ( $USER->user_id == $viewRow->user_id ) {
    ?><div align="right"><a href="http://<?=USER_DOMAIN?>/edit_top_friends.php">Edit your Friends.</a></div><?
  }
  ?>
</div>