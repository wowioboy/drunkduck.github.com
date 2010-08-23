<?
if ( !isset($_GET['cid']) ) return;
$CID = (int)$_GET['cid'];

$res = db_query("SELECT * FROM comics WHERE (user_id='".$USER->user_id."' OR secondary_author='".$USER->user_id."') AND comic_id='".$CID."'");
if ( db_num_rows($res) == 0 ) {
  db_free_result($res);
  return;
}
$COMIC_ROW = db_fetch_object($res);
db_free_result($res);


$res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
$row = db_fetch_object($res);
db_free_result($res);
$COMIC_ROW->total_pages = $row->total_pages;
db_query("UPDATE comics SET total_pages='".$COMIC_ROW->total_pages."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
?>

<script type="text/javascript">
  function updateCharCt(field, updateDiv, limit)
  {
    if ( field.value.length >= 255 ) {
      field.value = field.value.substring(0, 255);
      alert('You have reached the limit of the description size you are allowed.');
    }
    var div = document.getElementById(updateDiv);
    div.innerHTML = (limit-field.value.length)+" characters left.";
  }

  function make18() {
    ajaxCall('/xmlhttp/toggle_age.php?cid=<?=$CID?>&make18=1', updateInfo);
  }
  function makeUnder18() {
    ajaxCall('/xmlhttp/toggle_age.php?cid=<?=$CID?>', updateInfo);
  }
  function updateInfo(nfo) {
    document.getElementById('ageGroup').innerHTML = nfo;
  }
</script>

<h1 align="left">Editing <?=$COMIC_ROW->comic_name?></h1>

<?
if ( isset($_GET['rating']) )
{
  if ( $COMIC_ROW->flags & FLAG_RATING_LOCKED )
  {
    ?><div align="center" style="border:1px dashed white;width:400px;padding:5px;">You cannot change your rating. It has been locked by an administrator.</div><?
  }
  else if ( isset($RATINGS[$_GET['rating']]) )
  {
    $COMIC_ROW->rating_symbol = db_escape_string($_GET['rating']);
    db_query("UPDATE comics SET rating_symbol='".$COMIC_ROW->rating_symbol."' WHERE comic_id='".$COMIC_ROW->comic_id."'");


    ?><div align="center" style="border:1px dashed white;width:400px;padding:5px;">
        Your comic rating has been changed to:
        <br>
        <font style="font-size:24px;"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$_GET['rating']?>_lg.gif"></font>
        <br>
        <i><?=$RATINGS[$_GET['rating']]?></i>
      </div><?
  }
}
?>



<TABLE BORDER='0' CELLPADDING='0' CELLSPACING='20' WIDTH='800'>
  <TR>
    <TD WIDTH='400' VALIGN='TOP'>

<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Comic Info:</DIV>

  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>
    <FORM ACTION='submit_edits.php' METHOD='POST'>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Short Description:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <TEXTAREA NAME='comicDescription' STYLE='WIDTH:100%;' rows="5" onKeyUp="updateCharCt(this, 'charCt', 255);"><?=$COMIC_ROW->description?></TEXTAREA>
        <DIV ID='charCt'><?=(255-strlen($COMIC_ROW->description))?> Characters Left</DIV>
      </TD>
    </TR>
    <?
    if ( $USER->user_id == 1 )
    {
      ?>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Long Description:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <TEXTAREA NAME='comicDescription_long' STYLE='WIDTH:100%;' rows="5"><?=$COMIC_ROW->description_long?></TEXTAREA>
      </TD>
    </TR>
      <?
    }
    ?>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Total Pages:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <?=number_format($COMIC_ROW->total_pages)?>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Type:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <?
        foreach($COMIC_STYLES as $key=>$value) {
          echo "<INPUT TYPE='RADIO' NAME='comicType' VALUE='".$key."' ".(($COMIC_ROW->comic_type==$key)?"CHECKED":"").">".$value."<BR>";
        }
        ?>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Rating:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <DIV ID='ageGroup'>
          <?
          if ($COMIC_ROW->flags & FLAG_RATING_LOCKED)
          {
            if ( $COMIC_ROW->rating_symbol != 'M' && $COMIC_ROW->rating_symbol != 'A' )
            {
              $COMIC_ROW->flags &= ~(FLAG_RATING_LOCKED);
              db_query("UPDATE comics SET flags='".$COMIC_ROW->flags."' WHERE comic_id='".$COMIC_ROW->comic_id."'");
            }
          }


          if ( !($COMIC_ROW->flags & FLAG_RATING_LOCKED) )
          {
            $ct = 0;
            foreach($RATINGS as $symbol=>$description)
            {
              if ( $symbol == $COMIC_ROW->rating_symbol )
              {
                ?><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0" style="border:2px solid white;"><?
              }
              else
              {
                ?><a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$_GET['cid']?>&rating=<?=$symbol?>" title="<?=$description?>" alt="<?=$description?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0"></a><?
              }

              if ( ++$ct<count($RATINGS) ) {
                ?> | <?
              }
            }
          }
          else
          {
            echo "Admin Locked to: ".$COMIC_ROW->rating_symbol;
          }
          ?>
        </DIV>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Category:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <SELECT NAME='comicCat' STYLE='WIDTH:100%;'>
          <?=getKeyValueSelect($COMIC_CATS, $COMIC_ROW->category)?>
        </SELECT>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='LEFT' WIDTH='30%'>
        <B>Subcategory:</B>
      </TD>
      <TD ALIGN='LEFT' WIDTH='70%'>
        <SELECT NAME='comicSubCat' STYLE='WIDTH:100%;'>
          <?=getKeyValueSelect($COMIC_SUBCATS, $COMIC_ROW->subcategory)?>
        </SELECT>
      </TD>
    </TR>


    <tr>
      <td width="30%" align="left">
        <b>Writer</b>
      </td>
      <td width="70%" align="left">
        <input type="text" name="writer_name" style="width:100%;" value="<?=htmlentities($COMIC_ROW->writer_name, ENT_QUOTES)?>">
      </td>
    </tr>
    <tr>
      <td width="30%" align="left">
        <b>Illustrator</b>
      </td>
      <td width="70%" align="left">
        <input type="text" name="illustrator_name" style="width:100%;" value="<?=htmlentities($COMIC_ROW->illustrator_name, ENT_QUOTES)?>">
      </td>
    </tr>

    <tr>
      <td width="30%" align="left">
        <b>Editor</b>
      </td>
      <td width="70%" align="left">
        <input type="text" name="editor_name" style="width:100%;" value="<?=htmlentities($COMIC_ROW->editor_name, ENT_QUOTES)?>">
      </td>
    </tr>


    <TR>
      <TD COLSPAN='2' ALIGN='CENTER'>
        <INPUT TYPE='SUBMIT' VALUE='Change!'>
      </TD>
    </TR>
    <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$CID?>'>
    </FORM>

  </TABLE>
</DIV>

  </TD>
  <TD WIDTH='400' VALIGN='TOP'>

<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='CENTER'>
  <DIV CLASS='header' ALIGN='CENTER'>Miscellaneous:</DIV>

    <DIV STYLE='PADDING-LEFT:75px;' align="left">
      <LI><A HREF='http://<?=DOMAIN?>/account/edit_template.php?cid=<?=$COMIC_ROW->comic_id?>'>Edit Design Template</A>
      <LI><A HREF='http://<?=DOMAIN?>/account/upload_files.php?cid=<?=$COMIC_ROW->comic_id?>'>Manage EXTRA Files</A>
      <LI><A HREF='http://<?=DOMAIN?>/account/create_backup.php?cid=<?=$COMIC_ROW->comic_id?>'>Backup your Pages</A>
      <LI><A HREF='http://<?=DOMAIN?>/account/pageviews.php?cid=<?=$COMIC_ROW->comic_id?>'>Pageviews</A>

      <br>
      <br>
      <form enctype="multipart/form-data" action="send_thumb.php" method="POST">
      <LI>New Thumbnail: <input name="new_thumb" type="file" style="width:0px;border:0px;height:20px;"> <input type="submit" value="Send" style="border:0px;height:20px;">
      <input type="hidden" name="cid" value="<?=$COMIC_ROW->comic_id?>">
      </form>
    </DIV>
</DIV>

<BR><BR>
<?
if ( $USER->user_id == $COMIC_ROW->user_id )
{
  if ( $COMIC_ROW->secondary_author )
  {
    $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id='".$COMIC_ROW->secondary_author."'");
    $assist_row = db_fetch_object($res);
    db_free_result($res);
  }
?>
<DIV STYLE='WIDTH:400px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Assistant Username:</DIV>

  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>
    <FORM ACTION='change_assistant.php' METHOD='POST'>
    <TR>
      <TD ALIGN='CENTER'>
        <?
          if ( $assist_row->avatar_ext )
          {
            if ( $assist_row->avatar_ext == 'swf' ) {
              $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$assist_row->user_id.'.swf');
              echo get_flash_movie(IMAGE_HOST.'/avatars/avatar_'.$assist_row->user_id.'.swf', $INFO[0], $INFO[1]);
            }
            else {
              echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$assist_row->user_id.".".$assist_row->avatar_ext."'>";
            }
          }
        ?>
        <INPUT TYPE='TEXT' NAME='assistant' VALUE='<?=$assist_row->username?>' STYLE='WIDTH:100%;'>
      </TD>
    </TR>
    <TR>
      <TD ALIGN='CENTER'>
        <INPUT TYPE='SUBMIT' VALUE='Change'>
      </TD>
    </TR>
    <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$COMIC_ROW->comic_id?>'>
    </FORM>
  </TABLE>
</DIV>
<?
}
?>

    </TD>
  </TR>
</TABLE>

<DIV STYLE='WIDTH:820px;' CLASS='container' ALIGN='LEFT'>
  <DIV CLASS='header' ALIGN='CENTER'>Comic Pages:</DIV>

  <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='5' WIDTH='100%'>
    <TR>
      <TD ALIGN='CENTER' COLSPAN='5'>
        <A HREF='http://<?=DOMAIN?>/account/add_page.php?cid=<?=$CID?>'>Add a new page to this comic!</A>
      </TD>
    </TR>
    <?
    $P        = (int)$_GET['p'];
    $PER_PAGE = 50;
    $START    = $P*$PER_PAGE;
    $TOTAL    = $COMIC_ROW->total_pages;

    if ( $P > 0 ) {
      $PREV_PAGE = "<A HREF='".$_SERVER['PHP_SELF']."?cid=".$CID."&p=".($P-1)."'>Previous Page</A>";
    }
    if ( ($P*$PER_PAGE+$PER_PAGE) < $TOTAL ) {
      $NEXT_PAGE = "<A HREF='".$_SERVER['PHP_SELF']."?cid=".$CID."&p=".($P+1)."'>Next Page</A>";
    }

    $res = db_query("SELECT COUNT(*) as total_pages FROM comic_pages WHERE comic_id='".$CID."'");
    $row = db_fetch_object($res);
    db_free_result($res);
    $TOTAL_PAGES = $row->total_pages;

    $res = db_query("SELECT * FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id DESC LIMIT ".$START.",".$PER_PAGE);
    $ROW_CT      = db_num_rows($res);
    $START_POINT = $TOTAL_PAGES - $START;
    if ( $ROW_CT > 0 )
    {
      ?>
      <TR>
        <TD ALIGN='CENTER' COLSPAN='2' ALIGN='LEFT'><?=$PREV_PAGE?></TD>
        <TD ALIGN='CENTER'></TD>
        <TD ALIGN='CENTER' COLSPAN='2' ALIGN='RIGHT'><?=$NEXT_PAGE?></TD>
      </TR>
      <TR>
        <TD ALIGN='CENTER'><B>Page</B></TD>
        <TD ALIGN='CENTER'><B>Title</B></TD>
        <TD ALIGN='CENTER'><B>Date Live</B></TD>
        <TD ALIGN='CENTER'><B>Avg. Score</B></TD>
        <TD ALIGN='CENTER'><B>Action</B></TD>
      </TR>
      <?
      $COUNT = 0;
      $BAD = false;
      while ($row = db_fetch_object($res))
      {
        if ( $row->order_id != ($START_POINT-$COUNT) ) {
          $BAD = true;
          break;
        }
        ?>
        <TR>
          <TD ALIGN='LEFT'>
            <?=$row->order_id?>
          </TD>
          <TD ALIGN='LEFT'>
            <A HREF='http://<?=DOMAIN?>/<?=comicNameToFolder($COMIC_ROW->comic_name)?>/?p=<?=$row->page_id?>'><?=$row->page_title?></A>
          </TD>
          <TD ALIGN='CENTER'><?=date("m-d-Y", $row->post_date)?></TD>
          <TD ALIGN='CENTER'><?=$row->page_score?></TD>
          <TD ALIGN='CENTER'>
            <div style="float:left;width:75px;">
              <?
                if ( $row->order_id != $TOTAL ) {
                  ?><A HREF='move_page.php?dir=1&cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$row->page_id?>&p=<?=$P?>' TITLE='Move this page up!'><IMG SRC='<?=IMAGE_HOST?>/site_gfx/move_page_up.gif' BORDER='0'></A><?
                }
              ?>
              <?
                if ( $row->order_id > 1 ) {
                  ?><A HREF='move_page.php?dir=-1&cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$row->page_id?>&p=<?=$P?>' TITLE='Move this page down!'><IMG SRC='<?=IMAGE_HOST?>/site_gfx/move_page_down.gif' BORDER='0'></A><?
                }
              ?>
            </div>

            <div style="float:left;width:75px;">
              <A HREF='http://<?=DOMAIN?>/account/edit_page.php?cid=<?=$CID?>&pid=<?=$row->page_id?>'>Edit</A> | <A HREF='http://<?=DOMAIN?>/<?=comicNameToFolder($COMIC_ROW->comic_name)?>/pages/<?=md5($row->comic_id.$row->page_id)?>.<?=$row->file_ext?>' TARGET='_BLANK'>View</A>
            </div>

            <div style="float:right;width:100px;">
              <?
                if ( $row->is_chapter ) {
                  ?><A HREF='chapter_page.php?chap=0&cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$row->page_id?>&p=<?=$P?>' style="color:green;">Is a Chapter</A><?
                }
                else {
                  ?><A HREF='chapter_page.php?chap=1&cid=<?=$COMIC_ROW->comic_id?>&pid=<?=$row->page_id?>&p=<?=$P?>'>Isn't a Chapter</A><?
                }
              ?>
            </div>
          </TD>
        </TR>
        <?
        $COUNT++;
      }
    }
    if ($BAD) {
      $NEW_ARRAY = array();
      $res = db_query("SELECT page_id FROM comic_pages WHERE comic_id='".$CID."' ORDER BY order_id DESC");
      $ROW_CT      = db_num_rows($res);
      $ct          = 0;
      while($row = db_fetch_object($res)) {
        $NEW_ARRAY[$ROW_CT-$ct] = $row->page_id;
        $ct++;
      }
      db_free_result($res);

      foreach($NEW_ARRAY as $order_id=>$page_id) {
        db_query("UPDATE comic_pages SET order_id='".$order_id."' WHERE page_id='".$page_id."'");
      }

      header("Location: http://".DOMAIN."/account/edit_comic.php?cid=".$CID);
    }
    ?>
  </TABLE>
</DIV>