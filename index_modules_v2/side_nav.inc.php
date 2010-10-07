<?

if ( !$USER )
{
  if ( isset($_POST['un']) )
  {
    ?><div align="center" style="border:1px dashed white">Invalid Login!</div><?
  }
  ?>
  <form action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
  <img src="<?=IMAGE_HOST_SITE_GFX?>/mbrlogin.gif" width="175" height="76">
  <img src="<?=IMAGE_HOST_SITE_GFX?>/login_r1_c1.gif" height="20" width="60">
  <br>
  <input name="un" value="username" onfocus="this.value='';" style="height: 20px; width: 100px;" type="text">
  <br>
  <img src="<?=IMAGE_HOST_SITE_GFX?>/login_r2_c1.gif" height="20" width="60">
  <br>
  <input name="pw" value="password" onfocus="this.value='';" style="height: 20px; width: 100px;" type="password">
  <br>
  &nbsp;<input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_login.gif" width="56" height="14" border="0">
  <br>
  <p><a href="http://<?=DOMAIN?>/forgot_password.php">Forgot your password?</a></p>
  <img src="<?=IMAGE_HOST_SITE_GFX?>/mbrregister.gif" width="175" height="59" />
  <p>Not Registered? <br /><a href="http://<?=DOMAIN?>/signup/">Signup for a FREE Account!</a></p>
  <p><strong>Registered users can:</strong></p>
  <p><strong> Comment on comics!</strong></p>
  <p><strong>Create their own comics!</strong></p>
  <p><strong>Vote in polls and contests!</strong></p>
  <p><strong>Use the forums!</strong></p>
  <p><a href="/signup/"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_register.gif" width="56" height="14" border="0"></a></p>
  <form>
  <?
}
else
{
  ?>
  <script language="JavaScript">
    function killFav(favID)
    {
      if ( !confirm('Delete this favorite?') ) return;
      ajaxCall('/xmlhttp/removeFav.php?fav='+favID, onFavRes);
    }

    function onFavRes(resp)
    {
      if ( resp != 'noop' ) {
        $('fav_'+resp).style.display = 'none';
      }
    }

    <?
    if ( $USER->flags & FLAG_NEW_MAIL ) {
      ?>alert('You have NEW mail!');<?
      $USER->flags &= ~FLAG_NEW_MAIL;
      db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
    }
    ?>
  </script>
  <div id="userList">
  <table border="0" cellpadding="4" cellspacing="0" width="180">
    <tr>
      <td colspan="2">
        <p align="right">
          <a href="http://<?=USER_DOMAIN?>/<?=$USER->username?>"><img src="<?=IMAGE_HOST?>/process/user_<?=$USER->user_id?>.<?=$USER->avatar_ext?>" align="left" height="50" width="50" border="0"></a>
          Hi <?=$USER->username?>!
          <p align="right"><a href="http://<?=DOMAIN?>/account/overview/"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_myaccount.gif" width="56" height="14" border="0" /></a></p>
          <p align="right"><a href="http://<?=DOMAIN?>/?logout=<?=$USER->user_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/smbtns_logout.gif" width="56" height="14" border="0" /></a>
          <p align="right"><a href="http://<?=DOMAIN?>/community/message/inbox.php"><?
            if ( $USER->pending_mail < 1 )
            {
              ?><img src="<?=IMAGE_HOST?>/mail/mail.gif" border="0" alt="You have no new Private Quacks" title="You have no new Private Quacks"><?
            }
            else if ( $USER->pending_mail < 10 )
            {
              ?><img src="<?=IMAGE_HOST?>/mail/mail_new.gif" border="0" alt="You have unread Private Quacks!" title="You have unread Private Quacks!"><?
            }
            else
            {
              ?><img src="<?=IMAGE_HOST?>/mail/mail_lots.gif" border="0" alt="You have lots of unread Private Quacks!" title="You have lots of unread Private Quacks!"><?
            }
            ?></a>
          </p>
        </p>
      </td>
    </tr>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" width="180" class="userlist">
    <tr>
      <td colspan="2">

      </td>
    </tr>
    <tr>
      <td colspan="2"><img src="<?=IMAGE_HOST_SITE_GFX?>/memberside_myfaves.gif" height="25" width="180"></td>
    </tr>


    <?
      $ctr = 0;
      $COMIC_IDS = array();
      $res = db_query("SELECT comic_id FROM comic_favs WHERE user_id='".$USER->user_id."'");
      while($row = db_fetch_object($res)) {
        $COMIC_IDS[$row->comic_id] = $row;
      }
      db_free_result($res);

      if ( $USER->flags&FLAG_FAVS_BY_DATE ) {
        $res = db_query("SELECT comic_id, category, comic_name, last_update, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') ORDER BY last_update DESC");
      }
      else {
        $res = db_query("SELECT comic_id, category, comic_name, last_update, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') ORDER BY comic_name ASC");
      }

      $lastCat = "";
      $ROWS = array();
      $ct = 0;
      while ($row = db_fetch_object($res))
      {
          $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
          $bg  = 'odd';
          if ( ++$ct%2 == 0 ) {
            $bg = 'even';
          }
          $indicator = '';
          if  ( date("Ymd",$row->last_update) == YMD ) {
            $indicator = ' *';
          }

          ?>
          <tr ID='fav_<?=$row->comic_id?>'>
            <td height="20" valign="middle" width="150" class="fav_<?=$bg?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.$indicator?></a></td>
            <td height="20" valign="middle" width="20" class="fav_<?=$bg?>"><A HREF='JavaScript:killFav(<?=$row->comic_id?>);' ALT='Delete this favorite' TITLE='Delete this favorite'><img src="<?=IMAGE_HOST_SITE_GFX?>/remove_button.gif" width="12" height="12" border="0" /></a></td>
          </tr>
          <?
      }


      if ( count($COMIC_IDS) )
      {
        ?>
        <tr>
          <td colspan="2" align="center">
            <a href="http://<?=DOMAIN?>/account/manage_favs.php">Edit Favorites Preferences</a>
          </td>
        </tr>
        <?
      }
    ?>
  </table>

  <p>&nbsp;</p>

  <?
  $comicsArr = array();
  $assistArr = array();
  $res = db_query("SELECT * FROM comics WHERE user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."'");
  while($row = db_fetch_object($res) )
  {
    if ( $row->user_id == $USER->user_id ) {
      $comicsArr[] = $row;
    }
    else {
      $assistArr[] = $row;
    }
  }
  db_free_result($res);
  ?>

  <table align="center" border="0" cellpadding="0" cellspacing="0" width="180" class="userlist">
    <tr>
      <td colspan="2"><img src="<?=IMAGE_HOST_SITE_GFX?>/memberside_mycomics.gif" height="25" width="180"></td>
    </tr>
    <?
    if ( count($comicsArr) == 0 )
    {
      ?>
      <tr>
        <td bgcolor="#3300cc" height="20" valign="middle" width="130">you currently don't have any comics</td>
        <td align="center" bgcolor="#3300cc" height="20" valign="middle" width="50">&nbsp;</td>
      </tr>
      <?
    }
    else
    {
      $ct = 0;
      foreach($comicsArr as $row)
      {
        $bg  = 'odd';
        if ( ++$ct%2 == 0 ) {
          $bg = 'even';
        }
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        ?>
        <tr>
          <td height="20" valign="middle" width="130"  class="fav_<?=$bg?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.( (date("Ymd",$row->last_update)==YMD)?" *":"" )?></a></td>
          <td align="center" height="20" valign="middle" width="50" class="fav_<?=$bg?>">
            <a href="http://<?=DOMAIN?>/account/comic/add_page.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_addpage.gif" border="0" height="16" width="13"></a>
            <a href="http://<?=DOMAIN?>/account/comic/comic_design.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_edittemplate.gif" border="0" height="16" width="18"></a>
            <a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_info.gif" border="0" height="16" width="13"></a>
          </td>
        </tr>
        <?
      }
    }
    ?>
  </table>

  <p>&nbsp;</p>

  <table align="center" border="0" cellpadding="0" cellspacing="0" width="180" class="userlist">
    <tr>
      <td colspan="2"><img src="<?=IMAGE_HOST_SITE_GFX?>/memberside_assist.gif" height="25" width="180"></td>
    </tr>
    <?
    if ( count($assistArr) == 0 )
    {
      ?>
      <tr>
        <td bgcolor="#3300cc" height="20" valign="middle" width="130">you currently don't have any comics</td>
        <td align="center" bgcolor="#3300cc" height="20" valign="middle" width="50">&nbsp;</td>
      </tr>
      <?
    }
    else
    {
      $ct = 0;
      foreach($assistArr as $row)
      {
        $bg  = 'odd';
        if ( ++$ct%2 == 0 ) {
          $bg = 'even';
        }
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        ?>
        <tr>
          <td height="20" valign="middle" width="130"  class="fav_<?=$bg?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.( (date("Ymd",$row->last_update)==YMD)?" *":"" )?></a></td>
          <td align="center" height="20" valign="middle" width="50" class="fav_<?=$bg?>">
            <a href="http://<?=DOMAIN?>/account/comic/add_page.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_addpage.gif" border="0" height="16" width="13"></a>
            <a href="http://<?=DOMAIN?>/account/comic/edit_template.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_edittemplate.gif" border="0" height="16" width="18"></a>
            <a href="http://<?=DOMAIN?>/account/comic/edit_comic.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_info.gif" border="0" height="16" width="13"></a>
          </td>
        </tr>
        <?
      }
    }
    ?>
    <tr>
      <td colspan="2" height="20" valign="middle">
        <p>&nbsp;</p>
        <p><img src="<?=IMAGE_HOST_SITE_GFX?>/control_legend.gif" height="20" width="180"></p>
      </td>
    </tr>
  </table>

  <?
  if ( $USER->flags & FLAG_IS_ADMIN )
  {
    ?>
    <p>&nbsp;</p>

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="180" class="userlist">
      <tr>
        <td colspan="2"><a href="http://<?=DOMAIN?>/admin/"><img src="<?=IMAGE_HOST_SITE_GFX?>/admin_tools.gif" height="25" width="180" border="0"></a></td>
      </tr>

      <tr>
        <td width="130"><a href="http://<?=DOMAIN?>/admin/reported_comments.php">Reported Comments</a></td>
        <td width="50" align="center">
        <?
        $res = db_query("SELECT COUNT(*) as total FROM comment_reports WHERE handled=0");
        $row = db_fetch_object($res);
        db_free_result($res);
        echo number_format($row->total);
        ?>
        </td>
      </tr>

      <tr>
        <td><a href="http://<?=DOMAIN?>/admin/blog_post.php?a=list">News</a></td>
        <td></td>
      </tr>

      <tr>
        <td><a href="http://<?=DOMAIN?>/admin/featured_comics.php?a=list">Featured Comics</a></td>
        <td align="center"><?
        $res = db_query("SELECT COUNT(*) as total_unused FROM featured_comics WHERE ymd_date_live='0'");
        $row = db_fetch_object($res);
        db_free_result($res);
        echo $row->total_unused;
        ?></td>
      </tr>

      <form action="http://<?=DOMAIN?>/admin/user_view.php" method="GET">
      <tr>
        <td><a href="http://<?=DOMAIN?>/admin/user_view.php">User List</a></td>
        <td><input type="text" name="userSearch" style="width:50px;height:9px;font-size:9px;"></td>
      </tr>
      </form>

      <form action="http://<?=DOMAIN?>/admin/comic_list.php" method="GET">
      <tr>
        <td><a href="http://<?=DOMAIN?>/admin/comic_list.php">Comic List</a></td>
        <td><input type="text" name="comicsearch" style="width:50px;height:9px;font-size:9px;"></td>
      </tr>
      </form>

    </table>
    <?
  }
  ?>


  <br>
  <?
  /*
  $swf = new FlashMovie(IMAGE_HOST.'/swf/banMgr.swf', 30, 30);
  $swf->addVar('banVar', md5($USER->user_id.'fish') );
  if ( $clearBan ) {
    $swf->addVar('banned', 'clearMe');
  }
  else {
    $swf->addVar('bannedUser', $USER->username);
  }
  $swf->addVar('banURL', '/');
  $swf->setTransparent(true);
  $swf->showHTML();
  */
  ?>
  </div>
  <?
}