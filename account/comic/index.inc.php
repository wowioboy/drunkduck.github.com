<?
include('header_edit_comic.inc.php');

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









      <table border='0' cellpadding='0' cellspacing='0' width='100%'>
        <tr>
          <td colspan="2" align='LEFT' valign="top"><h3>Comic Overview:</h3></td>
        </tr>
        <tr>
          <td align='left' width="50%" valign="top" class="community_thrd">
            <strong>Comic Thumbnail:</strong><br>
            <font class="helpnote">
              DrunkDuck automatically creates a thumbnail from your first comic page.
              <br>
              You can replace it with one of your own making here.
              <br>
              Image size is 80x100 pixels.
            </font>
          </td>
          <td align='left' width="50%" valign="top" class="community_thrd">
            <img src="<?=thumb_processor($COMIC_ROW)?>" border="0">
            <br>
      			<form enctype="multipart/form-data" action="send_thumb.php" method="POST">
              New Thumbnail:
              <br>
              <input name="new_thumb" type="file" style="width:0px;border:0px;height:20px;">
              <input type="hidden" name="cid" value="<?=$COMIC_ROW->comic_id?>">
              <br>
              <input name="submit" type="submit" style="border:0px;height:20px;" value="Send">
          </form>
          </td>
        </tr>
        <tr>
          <td align='left' valign="top" class="community_thrd">
            <strong>Comic Title Image:</strong><br>
            <font class="helpnote">
              You can optionally upload a title graphic for your comic.<br>
              This can be displayed on your comic pages and forum(s).
            </font>
          </td>
          <td align='left' valign="top" class="community_thrd">
            <?
              $FOLDER_NAME = comicNameToFolder($COMIC_ROW->comic_name);

              if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.gif') ) {
                echo '<img src="http://'.DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.gif">';
              }
              if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.png') ) {
                echo '<img src="http://'.DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.png">';
              }
              if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.jpg') ) {
                echo '<img src="http://'.DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.jpg">';
              }
              if ( file_exists(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.swf') )
              {
                $INFO = getimagesize(WWW_ROOT.'/comics/'.$FOLDER_NAME{0}.'/'.$FOLDER_NAME.'/gfx/comic_title.swf');
                $swf = new FlashMovie('http://'.DOMAIN.'/'.$FOLDER_NAME.'/gfx/comic_title.swf', $INFO[0], $INFO[1]);
                $swf->setTransparent(true);
                echo $swf->getHTML();
              }
            ?>
            <br>
            <form enctype="multipart/form-data" action="send_title_image.php" method="POST">
              New Title Image:
              <br>
              <input name="new_title_image" type="file" style="width:0px;border:0px;height:20px;">
              <input type="hidden" name="cid" value="<?=$COMIC_ROW->comic_id?>">
              <br>
              <input name="submit" type="submit" style="border:0px;height:20px;" value="Send">
            </form>
          </td>
        </tr>
      </table>














      <a href="#" id="advanced_0_href" onClick="$('advanced_0').style.display='';$('advanced_0_href').innerHTML='';return false;">Show Advanced Comic Descriptions</a><br><br>
      <div style="display:none;" id="advanced_0">
        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
          <form action='submit_edits.php' method='POST'>
          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Short Description:<br>
              </b><div class="helpnote">This description appears in the mouseover in the &quot;Browse &amp; Search area. You can also make it display on your comics homepage. This description is limited to 255 characters. </div></td>
            <td colspan="2" align='LEFT' class="community_thrd">
             <TEXTAREA NAME='comicDescription' STYLE='WIDTH:100%;' rows="5" onKeyUp="updateCharCt(this, 'charCtshort', 255);"><?=htmlentities($COMIC_ROW->description, ENT_QUOTES)?></TEXTAREA>
              <div id='charCtshort' align="right" class="helpnote">
                <?=(255-strlen($COMIC_ROW->description))?> Characters Left
              </div></td>
          </tr>
          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Long Description:<br>
              </b><div class="helpnote">This optional description can be displayed on your comics homepage. </div></td>
            <td colspan="2" align='LEFT' class="community_thrd"><textarea name='comicDescription_long' style='WIDTH:100%;' rows="5" onKeyUp="updateCharCt(this, 'charCtlong', 10240);"><?=htmlentities($COMIC_ROW->description_long, ENT_QUOTES)?></textarea>
                <div id='charCtlong' align="right" class="helpnote">
                  <?=(10240-strlen($COMIC_ROW->description_long))?> Characters Left
                </div></td>
          </tr>
          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Total Comic Pages:</b> </td>
            <?
            $res = db_query("SELECT COUNT(*) as total_uploads FROM comic_pages WHERE comic_id='".$COMIC_ROW->comic_id."'");
            $row = db_fetch_object($res);
            ?>
            <td colspan="2" align='LEFT' class="community_thrd"><?=number_format($row->total_uploads)?> Uploaded / <?=number_format($COMIC_ROW->total_pages)?> Published</td>
          </tr>
          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Type of Comic:</b> </td>
            <td width="35%" align='LEFT' valign="top" class="community_thrd">
              <input type='RADIO' name='comicType' value='0' <?=(($COMIC_ROW->comic_type==0)?"CHECKED":"")?>>
              Comic Strip<br>
              <div class="helpnote">A comic &quot;strip&quot; is typically 3-4 panels in a strip, like you see in the newspaper. Stories in this format generally don't continue past 1 or 2 strips, but there are exceptions. </div><br>          <br>
            </td>
            <td width="35%" align='LEFT' valign="top" class="community_thrd">
              <input type='RADIO' name='comicType' value='1' <?=(($COMIC_ROW->comic_type==1)?"CHECKED":"")?>>
              Comic Book/Story<br>
              <div class="helpnote">A comic book/story is longer and more involved than a strip. Stories span multiple pages before concluding (if ever). A typical print comic book runs between 24 and 48 pages. </div>
            </td>
          </tr>
          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Rating:<br>
              </b><div class="helpnote">Some comics aren't for everyone. What is the appropriate audience for yours? </div></td>
            <td colspan="2" align='LEFT' class="community_thrd">
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
                  if ( $COMIC_ROW->rating_symbol == 'K' ) {
                    ?><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_K_sm.gif" border="0" style="border:2px solid white;" title="K for Kids" alt="K for Kids"> | <?
                  }

                  foreach($RATINGS as $symbol=>$description)
                  {
                    if ( $symbol == $COMIC_ROW->rating_symbol )
                    {
                      ?><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0" style="border:2px solid white;" title="<?=$description?>" alt="<?=$description?>"><?
                    }
                    else
                    {
                      ?><a href="<?=$_SERVER['PHP_SELF']?>?cid=<?=$_GET['cid']?>&rating=<?=$symbol?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$symbol?>_sm.gif" border="0" title="<?=$description?>" alt="<?=$description?>"></a><?
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
            </td>
          </tr>

          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Tone:</b></td>
            <td colspan="2" align='LEFT' class="community_thrd">
              <select name='newComicSubCat' style="width:100%;">
                <?=getKeyValueSelect($COMIC_SUBCATS, $COMIC_ROW->subcategory)?>
              </select>
            </td>
          </tr>
            <tr>
              <td width='30%' align='left' valign="top" class="community_thrd"><b>Style:<br>
                </b><div class="helpnote">What genre does your comic fit best into? </div></td>
              <td colspan="2" align='LEFT' class="community_thrd">
                <select name='search_style' STYLE='WIDTH:100%;'>
                  <?=getKeyValueSelect($COMIC_ART_STYLES, $COMIC_ROW->search_style)?>
                </select>
              </td>
            </tr>
            <tr>
              <td width='30%' align='left' valign="top" class="community_thrd"><b>Categories:<br>
                </b><div class="helpnote">When one is just not enough.</div></td>
              <td colspan="2" align='LEFT' class="community_thrd">
                <select name='search_category' STYLE='WIDTH:49%;'>
                  <?=getKeyValueSelect($COMIC_CATEGORIES, $COMIC_ROW->search_category)?>
                </select>
                <select name='search_category_2' STYLE='WIDTH:49%;'>
                  <?=getKeyValueSelect($COMIC_CATEGORIES, $COMIC_ROW->search_category_2)?>
                </select>
              </td>
            </tr>

          <tr>
            <td width='30%' align='LEFT' valign="top" class="community_thrd"><b>Language:</b></td>
            <td colspan="2" align='LEFT' class="community_thrd">
              <select name='lang' style='WIDTH:100%;'>
                <?=getKeyValueSelect($GLOBALS['TRANS']['TRANS_LANGUAGES'], $COMIC_ROW->language)?>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="3" align="left" valign="top">
              <div align="right">
                <input name="SUBMIT2" type='image' src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/comic_update.gif" value='Update Comic!'>
              </div>
            </td>
          </tr>
          <input type="hidden" name="cid" value="<?=$COMIC_ROW->comic_id?>">
          </form>

          <?
          if ( $_GET['del_cred'] ) {
            db_query("DELETE FROM credits WHERE comic_id='".$CID." AND credit_id='".(int)$_POST['del_cred']."'");
          }
          if ( $_POST['edit_credit_id'] == 'new' ) {
            db_query("INSERT INTO credits (comic_id, credit_name, credit_value) VALUES ('".$CID."', '".db_escape_string($_POST['credit_name'])."', '".db_escape_string($_POST['credit_value'])."')");
          }
          else if ( $_POST['edit_credit_id'] > 0 ) {
            if ( strlen($_POST['credit_name']) == 0 && strlen($_POST['credit_value']) == 0 ) {
              db_query("DELETE FROM credits WHERE credit_id='".(int)$_POST['edit_credit_id']."'");
            }
            else {
              db_query("UPDATE credits SET credit_name='".db_escape_string($_POST['credit_name'])."', credit_value='".db_escape_string($_POST['credit_value'])."' WHERE comic_id='".$CID."' AND credit_id='".(int)$_POST['edit_credit_id']."'");
            }
          }
          ?>
        </table>
      </div>










      <a href="#" id="advanced_1_href" onClick="$('advanced_1').style.display='';$('advanced_1_href').innerHTML='';return false;">Show Advanced Options</a><br><br>
      <div style="display:none;" id="advanced_1">
        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
          <tr>
            <td colspan="3" align="left" valign="top"><h4>Credits:</h4></td>
          </tr>
          <tr>
            <td align="left" valign="top"><div class="helpnote">Credit (Writer, Illustrator, etc.) </div></td>
            <td colspan="2" align="left" valign="top"><div class="helpnote">Person(s) responsible </div></td>
          </tr>
          <?
          $res = db_query("SELECT * FROM credits WHERE comic_id='".$COMIC_ROW->comic_id."' ORDER BY credit_name ASC");
          while($row = db_fetch_object($res))
          {
            ?>
            <form action="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>" method="POST">
            <tr>
              <td width="30%" align="left" valign="top"><input type="text" name="credit_name" style="width:95%;" value="<?=htmlentities($row->credit_name, ENT_QUOTES)?>"></td>
              <td width="100%" align="left" valign="top">
                <input type="text" name="credit_value" style="width:95%;" value="<?=htmlentities($row->credit_value, ENT_QUOTES)?>">
              </td>
              <td align="left" valign="top">
                <input name="SUBMIT" type='image' src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/credit_update.gif" value='Update Credit!'>
              </td>
            </tr>
            <input type="hidden" name="edit_credit_id" value="<?=$row->credit_id?>">
            <input type="hidden" name="cid" value="<?=$CID?>">
            </form>
            <?
          }
          ?>

          <form action="<?=$_SERVER['PHP_SELF']?>?cid=<?=$CID?>" method="POST">
          <tr id="new_form" style="display:none;">
            <td width="30%" align="left" valign="top"><input type="text" name="credit_name" style="width:95%;" value=""></td>
            <td width="100%" align="left" valign="top">
              <input type="text" name="credit_value" style="width:95%;" value="">
            </td>
            <td align="left" valign="top">
              <input name="SUBMIT" type='image' src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/credit_update.gif" value='Update Credit!'>
            </td>
          </tr>
          <input type="hidden" name="edit_credit_id" value="new">
          <input type="hidden" name="cid" value="<?=$CID?>">
          </form>

          <tr>
            <td align='CENTER' valign="top" class="community_thrd"><div align="left"><div class="helpnote">To add a field, click the &quot;Add Credit Field&quot; button. To delete a field just remove all the text from both the left and right fields and click &quot;Update Credits!&quot; </div></div></td>
            <td colspan='2' align='CENTER' valign="top" class="community_thrd">
              <div align="right">
                <a href="#" onClick="$('new_form').style.display='';$('addfieldBTN').style.display='none';return false;"><img id="addfieldBTN" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/credit_add.gif" title="Add Credit Field" alt="Add Credit Field"></a>
              </div>
            </td>
          </tr>

          <tr>
            <td colspan="3" align='CENTER' valign="top"><h4 align="left">Assistant:</h4></td>
          </tr>
          <tr>
            <td align='CENTER' valign="top" class="community_thrd">
              <div align="left"><strong>Assistant Username:</strong><br>
              <div class="helpnote">Your comic's  Assistant is another DrunkDuck member that you designate who can help you build and maintain your DrunkDuck comic. The Assistant can upload comic pages, edit your template and leave authors notes on uploaded pages.<br>
              <br>
              The Assistant can't delete your comic or remove you from your comic. To remove the Assistant, delete the name from the field and click &quot;Change Assistant&quot;. </div></div></td>
            <td align='CENTER' valign="top" class="community_thrd">
              <?
              if ( $USER->user_id == $COMIC_ROW->user_id )
              {
                if ( $COMIC_ROW->secondary_author )
                {
                  $res = db_query("SELECT user_id, username, avatar_ext FROM users WHERE user_id='".$COMIC_ROW->secondary_author."'");
                  $assist_row = db_fetch_object($res);
                  db_free_result($res);
                }
              }
              ?>
              <table border="0" cellpadding="0" cellspacing="5" width="100%">
                <FORM ACTION='send_change_assistant.php' METHOD='POST'>
                <tr>
                  <td align="center"><input name="assistant" value="<?=$assist_row->username?>" style="width: 100%;" type="text"></td>
                </tr>
                <tr>
                  <td align="center">
                    <div align="right">
                      <input value="Change" type="image" src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/assist_change.gif">
                    </div>
                  </td>
                </tr>
                <INPUT TYPE='HIDDEN' NAME='cid' VALUE='<?=$COMIC_ROW->comic_id?>'>
                </FORM>
              </table>
            </td>
            <td align='CENTER' valign="top" class="community_thrd">
              <div align="left">
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
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="3" align='left' valign="top"><h4>Comic Archive: </h4></td>
            </tr>
          <tr>
            <td align='left' valign="top" class="community_thrd"><div class="helpnote">Clicking the archive my comic button will create a downloadable archive of all your comic pages and extra uploaded files. </div></td>
            <td align='left' valign="top" class="community_thrd"><a href="http://<?=DOMAIN?>/account/comic/create_backup.php?cid=<?=$COMIC_ROW->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/edit_comic/comic_archive.gif" alt="#" width="100" height="24" border="0"></a></td>
            <td align='CENTER' valign="top" class="community_thrd">&nbsp;</td>
          </tr>
        </table>
      </div>









<?
include('footer_edit_comic.inc.php');
?>