<?
header("Location: http://www.drunkduck.com/account/overview/");

if ( $USER->username == 'Volte6' ) {
  include(WWW_ROOT.'/account/index_v2.inc.php');
  return;
}
?>
<style type="text/css">

<!--

/* Layout Stylesheet */


.controlsbox table {
	padding-top: 4px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
}
.controlsection {
	margin-right: 10px;
	margin-bottom: 10px;
	margin-left: 10px;
	background-color: #333333;
	padding: 0px 10px 10px 10px;
	border-top-width: 1px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #FFFFFF;
	border-right-color: #FFFFFF;
	border-bottom-color: #FFFFFF;
	border-left-color: #FFFFFF;
}
.controlsection table {
	margin: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

-->
</style>
<div id="bodyblock">
  <div class="content" align="left">
    <h1>My Controls</h1>
    <div class='content'><a href="#info">Account Info</a> | <a href="#karma">Karma</a> | <a href="#own">Comics You Own</a> | <a href="#assist">Comics You Assist</a></div>
    <div class="controlsection">
  <h3><a name="info" id="info"></a>Account Info:</h3>
  <TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='800'>
          <TR bgcolor="#005BAB">
            <TD width="300" ALIGN='LEFT'>
              <B>Username:</B>            </TD>
            <TD width="82%" ALIGN='LEFT'>
              <?=$USER->username?>            </TD>
          </TR>
          <TR bgcolor="#2382C3">
            <TD width="300" ALIGN='LEFT'>
              <B>Member Since:</B>            </TD>

            <TD ALIGN='LEFT'>
              <?=date("m-d-Y", $USER->signed_up)?>            </TD>
          </TR>
          <TR bgcolor="#005BAB">
            <TD width="300" ALIGN='LEFT'>
              <B>Avatar:</B>
              <DIV ALIGN='LEFT' CLASS='microalert'>100x100</DIV>
              <FORM ACTION='http://<?=DOMAIN?>/account/upload_avatar.php' METHOD='POST' ENCTYPE='multipart/form-data'>
                <INPUT TYPE='FILE' NAME='avatar' STYLE='WIDTH:100%;'><BR>
                <INPUT TYPE='SUBMIT' VALUE='Send!'>
              </FORM></TD>
            <TD ALIGN='LEFT'><?
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
              ?></TD>
          </TR>
      </TABLE>
	  </div>

    <?
      $karmaRow = get_karma_object($USER->user_id);
    ?>
  <div class="controlsection">
  <h3><a name="karma" id="karma"></a>Karma:</h3>
	          <TABLE BORDER='0' CELLPADDING='5' CELLSPACING='0' WIDTH='800'>
          <TR bgcolor="#005BAB">
            <TD ALIGN='LEFT'>
              <B>Comments Made:</B>            </TD>

            <TD ALIGN='LEFT'>
              <?=number_format($karmaRow->comments_made)?>            </TD>
          </TR>
          <TR bgcolor="#2382C3">
            <TD ALIGN='LEFT'>
              <B>Comments Muted:</B>            </TD>
            <TD ALIGN='LEFT'>

              <?=number_format($karmaRow->comments_muted)?> <SPAN CLASS='microalert'>(<?=round($karmaRow->comments_muted/$karmaRow->comments_made * 100, 1)?>%)</SPAN>            </TD>
          </TR>
          <TR bgcolor="#005BAB">
            <TD ALIGN='LEFT'>
              <B>Comments Reported:</B>            </TD>

            <TD ALIGN='LEFT'>
              <?=number_format($karmaRow->comments_reported)?> <SPAN CLASS='microalert'>(<?=round($karmaRow->comments_reported/$karmaRow->comments_made * 100, 1)?>%)</SPAN>            </TD>
          </TR>
          <TR bgcolor="#2382C3">
            <TD ALIGN='LEFT'>
              <B>Comments Erased:</B>            </TD>
            <TD ALIGN='LEFT'>
              <?=number_format($karmaRow->comments_erased)?> <SPAN CLASS='microalert'>(<?=round($karmaRow->comments_erased/$karmaRow->comments_made * 100, 1)?>%)</SPAN>            </TD>
          </TR>

          <TR bgcolor="#005BAB">
            <TD ALIGN='LEFT'>
              <B>Warning:</B>            </TD>
            <TD ALIGN='LEFT'>
              <?=number_format($USER->warning)?>%            </TD>
          </TR>
        </TABLE>
	  </div>


  <div class="controlsection">



          <h3><a name="own" id="own"></a>Comics You Own: </h3>
          <TABLE BORDER='0' CELLPADDING='3' CELLSPACING='0' WIDTH='800'>


       <?
        $res = db_query("SELECT * FROM comics WHERE user_id='".$USER->user_id."'");
        if ( db_num_rows($res) == 0 )
        {
          ?>
          <TR>
            <TD COLSPAN='6' ALIGN='CENTER'>
              <I>You don't own any comics...</I>
            </TD>
          </TR>
          <?
        }
        else
        {
          ?>
            <TR>
              <TD WIDTH='250' ALIGN='CENTER'>
                Comic Name              </TD>

              <TD WIDTH='100' ALIGN='CENTER'>
                Last Update              </TD>
              <TD WIDTH='50' ALIGN='CENTER'>
                Pages              </TD>
              <TD WIDTH='50' ALIGN='CENTER'>
                Comments              </TD>
              <TD WIDTH='200' ALIGN='CENTER'>
                Latest Comment              </TD>
              <TD WIDTH='50' ALIGN='CENTER'>
                Action              </TD>
            </TR>
          <?
          while ( $COMIC_ROW = db_fetch_object($res) )
          {
            $bg = "bgcolor=\"#2382C3\"";
            if ( ++$ct%2 == 0 ) {
              $bg = "bgcolor=\"#005BAB\"";
            }
            ?>
              <TR <?=$bg?>>
                <TD CLASS='blk_border'>
                  <A HREF='edit_comic.php?cid=<?=$COMIC_ROW->comic_id?>'><?=$COMIC_ROW->comic_name?><A>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?=(($COMIC_ROW->last_update!=0)?date("m-d-Y", $COMIC_ROW->last_update):"Never")?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?=number_format($COMIC_ROW->total_pages)?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?
                    $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
                    $row2 = db_fetch_object($res2);
                    db_free_result($res2);
                    echo number_format($row2);
                  ?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?
                    $res2 = db_query("SELECT * FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date DESC LIMIT 1");
                    $row2 = db_fetch_object($res2);
                    db_free_result($res2);
                    echo "<P STYLE='WIDTH:200px;HEIGHT:56px;OVERFLOW:AUTO;'><A HREF='http://".DOMAIN."/".str_replace(" ", "_", $COMIC_ROW->comic_name)."/?p=".$row2->page_id."' TARGET='_BLANK'>".$row2->comment."</A></P>";
                  ?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?
                    if ( $COMIC_ROW->user_id == $USER->user_id ) {
                      ?><A HREF='http://<?=DOMAIN?>/account/delete_comic.php?cid=<?=$COMIC_ROW->comic_id?>'>Delete</A><?
                    }
                  ?>
                </TD>
              </TR>
            <?
          }
        }
      ?>
            <TR>
              <TD COLSPAN='6' ALIGN='CENTER'><?=(!($USER->flags & FLAG_VERIFIED)?"<SPAN CLASS='alert'>You cannot create a comic until you verify your account.</SPAN>":"<A HREF='add_comic.php'>Add new comic!</A>")?>
              </TD>
            </TR>
        </TABLE>

	  </div>


  <div class="controlsection">
			<h3><a name="assist" id="assist"></a>Comics You Assist:            </h3>
			<TABLE BORDER='0' CELLPADDING='3' CELLSPACING='0' WIDTH='800'>
       <?
        $res = db_query("SELECT * FROM comics WHERE secondary_author='".$USER->user_id."'");
        if ( db_num_rows($res) == 0 )
        {
          ?>
          <TR>
            <TD COLSPAN='6' ALIGN='CENTER'>
              <I>You aren't assisting any comics...</I>
            </TD>
          </TR>
          <?
        }
        else
        {
          ?>
            <TR>
              <TD WIDTH='250' ALIGN='CENTER'>
                <B>Comic Name</B>
              </TD>
              <TD WIDTH='100' ALIGN='CENTER'>
                <B>Last Update</B>
              </TD>
              <TD WIDTH='50' ALIGN='CENTER'>
                <B>Pages</B>
              </TD>
              <TD WIDTH='50' ALIGN='CENTER'>
                <B>Comments</B>
              </TD>
              <TD WIDTH='200' ALIGN='CENTER'>
                <B>Latest Comment</B>
              </TD>
              <TD WIDTH='50' ALIGN='CENTER'>
                <B>Action</B>
              </TD>
            </TR>
          <?
          while ( $COMIC_ROW = db_fetch_object($res) )
          {
            $FOLDER = str_replace(' ', '_', $COMIC_ROW->comic_name);
            $bg = "bgcolor=\"#2382C3\"";
            if ( ++$ct%2 == 0 ) {
              $bg = "bgcolor=\"#005BAB\"";
            }
            ?>
              <TR <?=$bg?>>
                <TD CLASS='blk_border'>
                  <A HREF='edit_comic.php?cid=<?=$COMIC_ROW->comic_id?>'><?=$COMIC_ROW->comic_name?><A>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?=(($COMIC_ROW->last_update!=0)?date("m-d-Y", $COMIC_ROW->last_update):"Never")?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?=$COMIC_ROW->total_pages?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?
                    $res2 = db_query("SELECT COUNT(*) as total_comments FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."'");
                    $row2 = db_fetch_object($res2);
                    db_free_result($res2);
                    echo number_format($row2);
                  ?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  <?
                    $res2 = db_query("SELECT * FROM page_comments WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY post_date DESC LIMIT 1");
                    $row2 = db_fetch_object($res2);
                    db_free_result($res2);
                    echo "<P STYLE='WIDTH:200px;HEIGHT:56px;OVERFLOW:AUTO;'><A HREF='http://".DOMAIN."/".$FOLDER."/?p=".$row2->page_id."' TARGET='_BLANK'>".$row2->comment."</A></P>";
                  ?>
                </TD>
                <TD ALIGN='CENTER' CLASS='blk_border'>
                  &nbsp;
                </TD>
              </TR>
            <?
          }
        }
      ?>

      </TABLE>
</div>




</div>