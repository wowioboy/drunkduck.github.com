<?
include('header_overview.inc.php');

if ( $_POST['change_email'] ) {
  db_query("UPDATE demographics SET email='".db_escape_string(trim($_POST['change_email']))."' WHERE user_id='".$USER->user_id."'");
}

$res = db_query("SELECT * FROM demographics WHERE user_id='".$USER->user_id."'");
$DEMOGRAPHICS = db_fetch_object($res);
db_free_result($res);

?>
<div class="controlsection">

  <table border="0" cellpadding="5" cellspacing="0" width="100%"  class="controltable">
    <tr>
      <td colspan="4" align="left"><h3><a name="info" id="info"></a>Account Info:</h3></td>
    </tr>
    <tr>
      <td width="300" align="left" valign="top" class="community_thrd">
        <b>Username:</b>
      </td>
      <td width="82%" align="left" valign="top" class="community_thrd">
        <?=$USER->username?>
      </td>
      <?
        $karmaRow = get_karma_object($USER->user_id);
      ?>
      <td width="20" rowspan="4" align="left" valign="top" class="community_thrd">
          <table border="0" cellpadding="5" cellspacing="0" width="100" class="controltable">
            <tr>
              <td colspan="2" align="left" class="community_hdr"><a name="karma" id="karma"></a>Karma:</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#BBBBBB"><span class="date"> Comments Made: </span></td>
              <td align="right" valign="top" bgcolor="#BBBBBB"><strong><?=number_format($karmaRow->comments_made)?></strong></td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#BBBBBB"><span class="date"> Comments Muted: </span></td>
              <td align="right" valign="top" bgcolor="#BBBBBB"><strong> <?=number_format($karmaRow->comments_muted)?> <span class="date">(<?=round($karmaRow->comments_muted/$karmaRow->comments_made * 100, 1)?>%)</span> </strong></td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#BBBBBB"><span class="date"> Comments Reported: </span></td>
              <td align="right" valign="top" bgcolor="#BBBBBB"><strong> <?=number_format($karmaRow->comments_reported)?> <span class="date">(<?=round($karmaRow->comments_reported/$karmaRow->comments_made * 100, 1)?>%)</span> </strong></td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#BBBBBB"><span class="date"> Comments Erased: </span></td>
              <td align="right" valign="top" bgcolor="#BBBBBB"><strong> <?=number_format($karmaRow->comments_erased)?> <span class="style1">(<?=round($karmaRow->comments_erased/$karmaRow->comments_made * 100, 1)?>%)</span> </strong></td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#BBBBBB"><span class="date"> Warning: </span></td>
              <td align="right" valign="top" bgcolor="#BBBBBB"><strong> <?=number_format($USER->warning)?>% </strong></td>
            </tr>
          </table>
      </td>
      <td width="20" rowspan="4" align="left" valign="top" class="community_thrd">
        <table border="0" cellpadding="5" cellspacing="0" width="160" class="controltable">
          <tr>
            <td colspan="2" align="left" class="community_hdr"><a name="karma" id="karma"></a>The Duck Notifier:</td>
          </tr>
          <tr>
            <td colspan="2" align="left" bgcolor="#FFFFFF"><p align="center"><a href="http://www.drunkduck.com/downloads.php"><img src="http://www.drunkduck.com/gfx/site_gfx/DuckNotifier.jpg" border="0"></a></p>
              <p>Get notified from your Windows desktop when your favorites get updated! Also track how many people have favorited your comics! <span class="helpnote">(Requires Windows 2000 or XP and Microsoft .NET Framework) </span></p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <?
    if ( $USER->flags & FLAG_OVER_12 )
    {
      ?>
      <tr valign="top">
        <td align="left" class="community_thrd">
          <strong>E-mail address:<br>
        </td>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <td align="left" class="community_thrd">
          <div id="email_addy"><?=$DEMOGRAPHICS->email?> <a href="#" onClick="$('email_form').style.display='';$('email_addy').style.display='none';" style="font-size:9px;">change</a></div>
          <div id="email_form" style="display:none;"><input type="text" name="change_email" value="<?=$DEMOGRAPHICS->email?>" style="width:60%;"><input type="submit" value="Change"></div>
        </td>
        </form>
      </tr>
      <?
    }
    ?>
    <tr valign="top">
      <td width="300" align="left" class="community_thrd">
        <b>Member Since:</b>
      </td>
      <td align="left" class="community_thrd">
        <?=date('n-j-Y', $USER->signed_up)?>
      </td>
    </tr>
    <tr valign="top">
      <td width="300" align="left" class="community_thrd">
        <b>Avatar:</b>
        <div class="microalert" align="left">100x100</div>
        <form action='http://<?=DOMAIN?>/account/overview/upload_avatar.php' method='POST' enctype='multipart/form-data'>
          <input name="avatar" type="file">
          <br>
          <input value="Send!" type="submit">
        </form>
      </td>
      <td align="left" class="community_thrd">
        <?
          if ( $USER->avatar_ext )
          {
            if ( $USER->avatar_ext == 'swf' ) {
              $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$USER->user_id.'.swf');
              echo get_flash_movie(IMAGE_HOST.'/avatars/avatar_'.$USER->user_id.'.swf', $INFO[0], $INFO[1]);
            }
            else {
              echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$USER->user_id.".".$USER->avatar_ext."'>";
            }
          }
        ?>
      </td>
    </tr>

    <form action='http://<?=DOMAIN?>/account/overview/pw_change.php' method='POST'>
    <tr valign="top">
      <td width="300" align="left" class="community_thrd">
        <b>Change Password:</b>
      </td>
      <td align="left" class="community_thrd">
        Enter Current:<br>
        <input type="password" name="pw_now">
        <br>
        Enter New:<br>
        <input type="password" name="pw_change">
        <br>
        Confirm New:<br>
        <input type="password" name="pw_change_confirm">
        <br>
        <input type="submit" value="Change">
      </td>
    </tr>
    </form>

  </table>
  <!-- END AVATAR -->
  <table border="0" cellpadding="3" cellspacing="0" width="100%" class="controltable">
    <tr>
      <td colspan="6"><h4><a name="own" id="own"></a>Comics You Own: </h4></td>
    </tr>

    <?
    $res = db_query("SELECT * FROM comics WHERE user_id='".$USER->user_id."'");
    if ( db_num_rows($res) == 0 )
    {
      ?>
      <tr>
        <td colspan='6' align='center'>
          <i>You don't own any comics...</i>
        </td>
      </tr>
      <?
    }
    else
    {
      ?>
       <tr>
        <td width="250" align="center" class="community_hdr">
          Comic
        </td>
        <td width="100" align="center" class="community_hdr">
          Last Update
        </td>
        <td width="50" align="center" class="community_hdr">
          Pages
        </td>
        <td width="50" align="center" class="community_hdr">
          Comments
        </td>
        <td width="200" align="center" class="community_hdr">
          Latest Comment
        </td>
        <td width="50" align="center" class="community_hdr">
          Action
        </td>
      </tr>
      <?
      while ( $COMIC_ROW = db_fetch_object($res) )
      {
        ?>
        <tr >
          <td valign="top" class="community_thrd">
            <a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=thumb_processor($COMIC_ROW)?>" border="0"><?=$COMIC_ROW->comic_name?> </a>
          </td>
          <td align="center" valign="top" class="community_thrd">
            <?=(($COMIC_ROW->last_update!=0)?date("m-d-Y", $COMIC_ROW->last_update):"Never")?>
          </td>
          <td align="center" valign="top" class="community_thrd">
            <?=number_format($COMIC_ROW->total_pages)?>
          </td>
          <td align="center" valign="top" class="community_thrd">
            <?
              $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
              $row2 = db_fetch_object($res2);
              db_free_result($res2);
              echo number_format($row2->total_comments);
            ?>
          </td>
          <td align="center" valign="top" class="controltable_comments">
            <?
              $res2 = db_query("SELECT * FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date DESC LIMIT 1");
              $row2 = db_fetch_object($res2);
              db_free_result($res2);
            ?>
            <p align="justify" style="overflow: auto; width: 200px; height: 56px;">
              <a href="http://<?=DOMAIN?>/<?=str_replace(" ", "_", $COMIC_ROW->comic_name)?>/?p=<?=$row2->page_id?>" target="_BLANK"><?=htmlentities($row2->comment, ENT_QUOTES)?></a>
            </p>
          </td>
          <td align="left" valign="top" class="community_thrd">
            <p><a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a>&nbsp;</p>
            <p><a href="http://<?=DOMAIN?>/<?=str_replace(" ", "_", $COMIC_ROW->comic_name)?>/"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_view.gif" TITLE='View the image for this page!' width="41" height="16" border="0"></a>&nbsp;</p>
            <?
              if ( $COMIC_ROW->user_id == $USER->user_id ) {
                ?><p><a href="delete_comic.php?cid=<?=$COMIC_ROW->comic_id?>" onClick="return confirm('Are you SURE you want to delete this comic?');"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_del.gif" TITLE='Delete this comic!' width="15" height="16" border="0"></a></p><?
              }
            ?>
          </td>
        </tr>
        <?
      }
    }
    ?>
    <tr>
      <td colspan='6' align='right'>
        <a href='add_comic.php'><img src="<?=IMAGE_HOST_SITE_GFX?>/account_overview/comic_new.gif" width="100" height="24" border="0"></a>          </td>
    </tr>
  </table>


  <?
  if ( !($USER->flags & FLAG_ACCT_SIMPLE_MODE) )
  {
    ?>
    <table border="0" cellpadding="3" cellspacing="0" width="100%" class="controltable">
      <tr>
        <td colspan="6"><h4><a name="own" id="own"></a>Comics You Assist: </h4></td>
      </tr>

      <?
      $res = db_query("SELECT * FROM comics WHERE secondary_author='".$USER->user_id."'");
      if ( db_num_rows($res) == 0 )
      {
        ?>
        <tr>
          <td colspan='6' align='center'>
            <i>You don't own any comics...</i>
          </td>
        </tr>
        <?
      }
      else
      {
        ?>
         <tr>
          <td width="250" align="center" class="community_hdr">
            Comic
          </td>
          <td width="100" align="center" class="community_hdr">
            Last Update
          </td>
          <td width="50" align="center" class="community_hdr">
            Pages
          </td>
          <td width="50" align="center" class="community_hdr">
            Comments
          </td>
          <td width="200" align="center" class="community_hdr">
            Latest Comment
          </td>
          <td width="50" align="center" class="community_hdr">
            Action
          </td>
        </tr>
        <?
        while ( $COMIC_ROW = db_fetch_object($res) )
        {
          ?>
          <tr >
            <td valign="top" class="community_thrd">
              <a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=thumb_processor($COMIC_ROW)?>" border="0"><?=$COMIC_ROW->comic_name?> </a>
            </td>
            <td align="center" valign="top" class="community_thrd">
              <?=(($COMIC_ROW->last_update!=0)?date("m-d-Y", $COMIC_ROW->last_update):"Never")?>
            </td>
            <td align="center" valign="top" class="community_thrd">
              <?=number_format($COMIC_ROW->total_pages)?>
            </td>
            <td align="center" valign="top" class="community_thrd">
              <?
                $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);
                echo number_format($row2->total_comments);
              ?>
            </td>
            <td align="center" valign="top" class="controltable_comments">
              <?
                $res2 = db_query("SELECT * FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date DESC LIMIT 1");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);
              ?>
              <p align="justify" style="overflow: auto; width: 200px; height: 56px;">
                <a href="http://<?=DOMAIN?>/<?=str_replace(" ", "_", $COMIC_ROW->comic_name)?>/?p=<?=$row2->page_id?>" target="_BLANK"><?=htmlentities($row2->comment, ENT_QUOTES)?></a>
              </p>
            </td>
            <td align="left" valign="top" class="community_thrd">
              <p><a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit.gif" TITLE='Edit this page!' width="41" height="16" border="0"></a>&nbsp;</p>
              <p><a href="http://<?=DOMAIN?>/<?=str_replace(" ", "_", $COMIC_ROW->comic_name)?>/"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/edit_view.gif" TITLE='View the image for this page!' width="41" height="16" border="0"></a>&nbsp;</p>
            </td>
          </tr>
          <?
        }
      }
      ?>
    </table>
    <?
  }
  ?>

</div>

<?
include('footer_overview.inc.php');
?>