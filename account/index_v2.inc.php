<h1 align="left">My Controls </h1>

<div class="pagecontent">
  <a href="#info">Account Info</a> | <a href="#karma">Karma</a> | <a href="#own">Comics You Own</a> | <a href="#assist">Comics You Assist</a>


  <div class="controlsection">

    <table border="0" cellpadding="5" cellspacing="0" width="100%"  class="controltable">
      <tr bgcolor="#005bab">
        <td colspan="2" align="left" class="controltable_hdr"><h3><a name="info" id="info"></a>Account Info:</h3></td>
      </tr>
      <tr bgcolor="#001D37">
        <td width="300" align="left" class="community_thrd">
          <b>Username:</b>
        </td>
        <td width="82%" align="left" class="community_thrd">
          <?=$USER->username?>
        </td>
      </tr>
      <tr>
        <td width="300" align="left" class="community_thrd">
          <b>Member Since:</b>
        </td>
        <td align="left" class="community_thrd">
          <?=date("m-d-Y", $USER->signed_up)?>
        </td>
      </tr>
      <tr bgcolor="#001D37">
        <td width="300" align="left" class="community_thrd">
          <b>Avatar:</b>
          <div class="microalert" align="left">100x100</div>
          <form action='http://<?=DOMAIN?>/account/upload_avatar.php' method='POST' enctype='multipart/form-data'>
            <input name="avatar" style="width: 100%;" type="file">
            <br>
            <input value="Send!" type="submit">
          </form>
        </td>
        <td align="left" class="community_thrd">
        <a href="http://<?=DOMAIN?>/downloads.php"><img src="<?=IMAGE_HOST?>/site_gfx/DuckNotifier.jpg" align="right" border="0"></a>
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
    </table>
    <!-- END AVATAR -->

    <?
      $karmaRow = get_karma_object($USER->user_id);
    ?>
    <table border="0" cellpadding="5" cellspacing="0" width="100%" class="controltable">
      <tr bgcolor="#005bab">
        <td colspan="2" align="left" class="controltable_hdr">
          <h3><a name="karma" id="karma"></a>Karma:</h3>
        </td>
      </tr>
      <tr bgcolor="#001D37">
        <td align="left" class="community_thrd">
          Comments Made:
        </td>
        <td align="left" class="community_thrd">
          <?=number_format($karmaRow->comments_made)?>
        </td>
      </tr>
      <tr>
        <td align="left" class="community_thrd">
          Comments Muted:
        </td>
        <td align="left" class="community_thrd">
          <?=number_format($karmaRow->comments_muted)?> (<?=round($karmaRow->comments_muted/$karmaRow->comments_made * 100, 1)?>%)
        </td>
      </tr>
      <tr bgcolor="#001D37">
        <td align="left" class="community_thrd">
          Comments Reported:
        </td>
        <td align="left" class="community_thrd">
          <?=number_format($karmaRow->comments_reported)?> (<?=round($karmaRow->comments_reported/$karmaRow->comments_made * 100, 1)?>%)
        </td>
      </tr>
      <tr>
        <td align="left" class="community_thrd">
          Comments Erased:
        </td>
        <td align="left" class="community_thrd">
          <?=number_format($karmaRow->comments_erased)?> (<?=round($karmaRow->comments_erased/$karmaRow->comments_made * 100, 1)?>%)
        </td>
      </tr>
      <tr bgcolor="#001D37">
        <td align="left" class="community_thrd">
          Warning:
        </td>
        <td align="left" class="community_thrd">
          <?=number_format($USER->warning)?>%
        </td>
      </tr>
    </table>







    <table border="0" cellpadding="3" cellspacing="0" width="100%" class="controltable">
      <tr>
        <td colspan="6" align="center" class="controltable_hdr"><h3 align="left"><a name="own" id="own"></a>Comics You Own: </h3></td>
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
          <td width="250" align="center" class="controltable_subhdr">
            Comic Name
          </td>
          <td width="100" align="center" class="controltable_subhdr">
            Last Update
          </td>
          <td width="50" align="center" class="controltable_subhdr">
            Pages
          </td>
          <td width="50" align="center" class="controltable_subhdr">
            Comments
          </td>
          <td width="200" align="center" class="controltable_subhdr">
            Latest Comment
          </td>
          <td width="50" align="center" class="controltable_subhdr">
            Action
          </td>
        </tr>
        <?
        while ( $COMIC_ROW = db_fetch_object($res) )
        {
          $bg = "";
          if ( ++$ct%2 == 0 ) {
            $bg = "bgcolor=\"#001D37\"";
          }
          ?>

          <tr <?=$bg?>>
            <td class="community_thrd">
              <a href="edit_comic.php?cid=<?=$COMIC_ROW->comic_id?>"><?=$COMIC_ROW->comic_name?><a>
            </td>
            <td align="center" class="community_thrd">
              <?=(($COMIC_ROW->last_update!=0)?date("m-d-Y", $COMIC_ROW->last_update):"Never")?>
            </td>
            <td align="center" class="community_thrd">
              <?=number_format($COMIC_ROW->total_pages)?>
            </td>
            <td align="center" class="community_thrd">
              <?
                $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);
                echo number_format($row2->total_comments);
              ?>
            </td>
            <td align="center" class="controltable_comments">
              <?
                $res2 = db_query("SELECT * FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date DESC LIMIT 1");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);
              ?>
              <p align="justify" style="overflow: auto; width: 200px; height: 56px;">
                <a href="http://<?=DOMAIN?>/<?=str_replace(" ", "_", $COMIC_ROW->comic_name)?>/?p=<?=$row2->page_id?>" target="_BLANK"><?=htmlentities($row2->comment, ENT_QUOTES)?></a>
              </p>
            </td>
            <td align="center" class="community_thrd">
              <?
                if ( $COMIC_ROW->user_id == $USER->user_id ) {
                  ?><a href="http://<?=DOMAIN?>/account/delete_comic.php?cid=<?=$COMIC_ROW->comic_id?>">Delete</a><?
                }
              ?>
            </td>
          </tr>
          <?
        }
      }
        ?>
        <tr>
          <td colspan='6' align='center'>
            <?=(!($USER->flags & FLAG_VERIFIED)?"You cannot create a comic until you verify your account.":"<a href='add_comic.php'>Add new comic!</a>")?>
          </td>
        </tr>
    </table>




    <table border="0" cellpadding="3" cellspacing="0" width="100%" class="controltable">
      <tr>
        <td colspan="6" align="center" class="controltable_hdr"><h3 align="left"><a name="own" id="own"></a>Comics You Assist: </h3></td>
      </tr>

     <?
      $res = db_query("SELECT * FROM comics WHERE secondary_author='".$USER->user_id."'");
      if ( db_num_rows($res) == 0 )
      {
        ?>
        <tr>
          <td colspan='6' align='center'>
            <i>You aren't assisting any comics...</i>
          </td>
        </tr>
        <?
      }
      else
      {
        ?>
        <tr>
          <td width="250" align="center" class="controltable_subhdr">
            Comic Name
          </td>
          <td width="100" align="center" class="controltable_subhdr">
            Last Update
          </td>
          <td width="50" align="center" class="controltable_subhdr">
            Pages
          </td>
          <td width="50" align="center" class="controltable_subhdr">
            Comments
          </td>
          <td width="200" align="center" class="controltable_subhdr">
            Latest Comment
          </td>
          <td width="50" align="center" class="controltable_subhdr">
            Action
          </td>
        </tr>
        <?
        while ( $COMIC_ROW = db_fetch_object($res) )
        {
          $bg = "";
          if ( ++$ct%2 == 0 ) {
            $bg = "bgcolor=\"#001D37\"";
          }
          ?>

          <tr <?=$bg?>>
            <td class="community_thrd">
              <a href="edit_comic.php?cid=<?=$COMIC_ROW->comic_id?>"><?=$COMIC_ROW->comic_name?><a>
            </td>
            <td align="center" class="community_thrd">
              <?=(($COMIC_ROW->last_update!=0)?date("m-d-Y", $COMIC_ROW->last_update):"Never")?>
            </td>
            <td align="center" class="community_thrd">
              <?=number_format($COMIC_ROW->total_pages)?>
            </td>
            <td align="center" class="community_thrd">
              <?
                $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);
                echo number_format($row2->total_comments);
              ?>
            </td>
            <td align="center" class="controltable_comments">
              <?
                $res2 = db_query("SELECT * FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date DESC LIMIT 1");
                $row2 = db_fetch_object($res2);
                db_free_result($res2);
              ?>
              <p align="justify" style="overflow: auto; width: 200px; height: 56px;">
                <a href="http://<?=DOMAIN?>/<?=str_replace(" ", "_", $COMIC_ROW->comic_name)?>/?p=<?=$row2->page_id?>" target="_BLANK"><?=htmlentities($row2->comment, ENT_QUOTES)?></a>
              </p>
            </td>
            <td align="center" class="community_thrd">
              &nbsp;
            </td>
          </tr>
          <?
        }
      }
        ?>
    </table>




	</div>

</div>