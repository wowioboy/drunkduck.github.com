<div>

  <script type="text/javascript">
    function makeFriend(friend_id) {
      var grabAjax = new Ajax.Request( '/xmlhttp/add_friend.php', { method: 'post', parameters: 'friend_id='+friend_id, onComplete: onAddFriend} );
    }

    function onAddFriend( originalReq )
    {
      if (originalReq.responseText == '99') {
        $('make_friend').innerHTML = '<b><?=$viewRow->username?> is your friend.</b>';
      }
      else {
        alert('<?=$viewRow->username?> is already your friend. ('+originalReq.responseText+')');
      }
    }
  </script>

  <table border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td align="middle" valign="top">
        <?
        if ( IS_PUBLISHER == 1 )
        {
          ?><div align="center" style="width:100px;padding:3px;background:url(<?=$IMAGE_FOLDER?>/page_title_bg.gif);">
              <img src="<?=IMAGE_HOST?>/process/user_<?=$viewRow->user_id?>.<?=$viewRow->avatar_ext?>" style="border:0px;">
              <div align="left">
                <img src="<?=$IMAGE_FOLDER?>/publisher_stamp.gif" style="border:0px;">
              </div>
            </div><?
        }
        else  {
          ?><img src="<?=IMAGE_HOST?>/process/user_<?=$viewRow->user_id?>.<?=$viewRow->avatar_ext?>"><?
        }
        ?>
      </td>
      <td align="left" valign="top">
        <div style="font-size:18px;font-weight:bold;margin-bottom:25px;"><?=$viewRow->username?></div>
        <a href="http://<?=DOMAIN?>/community/message/author.php?to=<?=$viewRow->username?>">Send <?=$viewRow->username?> a PQ!</a>
        <?
        if ( $USER->user_id == $viewRow->user_id )
        {
          if ( ($USER->flags & FLAG_PUBLISHER_QUAL) && ($USER->flags & FLAG_IS_PUBLISHER) )
          {
            // Offer to remove publisher status.
            ?>
            <div id="make_friend">
              <a href="http://<?=USER_DOMAIN?>/<?=$USER->username?>/&publisher=0">Remove Publisher Status</a>
            </div>
            <?
          }
          else if ( ($USER->flags & FLAG_PUBLISHER_QUAL) )
          {
            // Offer to add publisher status.
            ?>
            <div id="make_friend">
              <a href="http://<?=USER_DOMAIN?>/<?=$USER->username?>/&publisher=1">Accept Publisher Status</a>
            </div>
            <?
          }
        }
        else if ( $USER && !$GLOBALS['FRIEND_DATA']->they_are_your_friend )
        {
          ?>
          <div id="make_friend">
            <a href="#" onClick="makeFriend(<?=$viewRow->user_id?>);return false;">Add <?=$viewRow->username?> to your friends list!</a>
          </div>
          <?
        }
        else if ( $GLOBALS['FRIEND_DATA']->they_are_your_friend )
        {
          ?><div id="make_friend">
              <b><?=$viewRow->username?> is your friend.</b> (<a href="http://<?=USER_DOMAIN?>/edit_top_friends.php?p=1&top=<?=$viewRow->user_id?>">Make TOP Friend</a>)
            </div><?
        }
        ?>
      </td>
    </tr>
  </table>

  <p>&nbsp;</p>


  <p align="left" id="about_self">
    <?
    if ( $_GET['editSelf'] && ( ($USER->user_id == $viewRow->user_id) || ($USER->flags & FLAG_IS_ADMIN) ) ) {
      ?><form action="http://<?=USER_DOMAIN?>/<?=$viewRow->username?>" method="POST"><textarea name="about_self" style="width:99%;" rows="10"><?=htmlentities( html_entity_decode($viewRow->about_self, ENT_QUOTES), ENT_QUOTES)?></textarea><br><input type="submit" value="Save"></form><?
    }
    else {
      echo nl2br( community_bb_code($viewRow->about_self, array(315, 260) ) );
    }

    if ( !$_GET['editSelf'] && ( ($USER->user_id == $viewRow->user_id) || ($USER->flags & FLAG_IS_ADMIN) ) ) {
      ?><div align="right"><a href="http://<?=USER_DOMAIN?>/<?=$viewRow->username?>/&editSelf=1">Edit About <?=( ($USER->user_id == $viewRow->user_id)?'Yourself':$viewRow->username )?></a></div><?
    }
    ?>
  </p>

  <? include(WWW_ROOT.'/ads/ad_includes/main_template/300x250b.html'); ?>

  <p>&nbsp;</p>
</div>