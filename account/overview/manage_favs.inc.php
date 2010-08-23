<?
include('header_overview.inc.php');
?>
<DIV CLASS='container' ALIGN='LEFT'>

  <?
  $HOME_PAGE = false;
  $res = db_query("SELECT flags FROM comics WHERE user_id='".$USER->user_id."'");
  while( $row = db_fetch_object($res) )
  {
    if ( $row->flags & FLAG_USE_HOMEPAGE ) {
      $HOME_PAGE = true;
    }
  }

  if ( isset($_POST['sort_by']) )
  {
    if ( $_POST['sort_by'] == FLAG_FAVS_BY_DATE ) {
      $USER->flags &= ~FLAG_FAVS_BY_CAT;
      $USER->flags |= FLAG_FAVS_BY_DATE;
      db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
    }
    else if ( $_POST['sort_by'] == FLAG_FAVS_BY_CAT ) {
      $USER->flags &= ~FLAG_FAVS_BY_DATE;
      $USER->flags |= FLAG_FAVS_BY_CAT;
      db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
    }
    else {
      $USER->flags &= ~FLAG_FAVS_BY_DATE;
      $USER->flags &= ~FLAG_FAVS_BY_CAT;
      db_query("UPDATE users SET flags='".$USER->flags."' WHERE user_id='".$USER->user_id."'");
    }
  }
  //Delete section
  if(isset($_REQUEST['d_comic_id'])){
	$delete_fav_comic_id = $_REQUEST['d_comic_id'];
        $delete_sql = "DELETE FROM comic_favs WHERE user_id='".$USER->user_id."' AND comic_id =$delete_fav_comic_id";
	//echo $delete_sql;
        db_query($delete_sql);
  }

  $recommends = array();
  $emails     = array();
  foreach($_POST as $key=>$value)
  {
    if ( substr($key, 0, 6) == 'email_' )
    {
      $key = substr($key, 6);
      if ( $value == 1 ) {
        $emails[] = (int)$key;
      }
    }
    else if ( substr($key, 0, 10) == 'recommend_' )
    {
      $key = substr($key, 10);
      if ( $value != 1 ) $value = 0;
      $recommends[] = (int)$key;
    }
    else if ( substr($key, 0, 4) == 'cat_' )
    {
      $key = (int)substr($key, 4);
      db_query("UPDATE comic_favs SET category='".db_escape_string(trim($value))."' WHERE user_id='".$USER->user_id."' AND comic_id='".$key."'");
    }
  }

  if ( count($recommends) )
  {
    db_query("UPDATE comic_favs SET recommend='0' WHERE user_id='".$USER->user_id."' AND comic_id NOT IN ('".implode("','", $recommends)."')");
    db_query("UPDATE comic_favs SET recommend='1' WHERE user_id='".$USER->user_id."' AND comic_id IN ('".implode("','", $recommends)."')");
  }
  

  if ( count($emails) )
  {
    db_query("UPDATE comic_favs SET email_on_update='0' WHERE user_id='".$USER->user_id."' AND comic_id NOT IN ('".implode("','", $emails)."')");
    db_query("UPDATE comic_favs SET email_on_update='1' WHERE user_id='".$USER->user_id."' AND comic_id IN ('".implode("','", $emails)."')");
  }

  ?>

  <form name="update_favorate" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <TABLE WIDTH='100%' BORDER='0' CELLPADDING='0' CELLSPACING='0'>
    <TR>
      <TD COLSPAN='4' ALIGN='CENTER' valign="bottom">
        <h3 align="left"><span style="float:left;">Favorites:</span><span style="float:right;"><input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/account_overview/faves_update.gif" width="100" height="24" border="0" align="baseline"></span></h3>
      </TD>
    </TR>
    <tr>
      <td height="20" colspan="4" align="left" valign="middle" class="community_thrd">
        Sort Favorites:
      <select name="sort_by">
        <option value="<?=FLAG_FAVS_BY_DATE?>" <?=(($USER->flags&FLAG_FAVS_BY_DATE)?"SELECTED":"")?>>Sort by Update</option>
        <option value="0" <?=(!($USER->flags&FLAG_FAVS_BY_DATE)?"SELECTED":"")?>>Sort by Name</option>
      </select>
      </td>
    </tr>
    <tr>
      <td height="20" align="left" valign="middle" bgcolor="#000000" class="community_hdr">
        <b>Comic</b>
      </td>
      <td width="50" height="20" align="center" valign="middle" bgcolor="#000000" class="community_hdr">
        <b>Recommended</b>
      </td>
      <td width="150" height="20" align="center" valign="middle" bgcolor="#000000" class="community_hdr">
        <b>Receive Email</b>
      </td>
      <td width="50" height="20" align="center" valign="middle" bgcolor="#000000" class="community_hdr">
        <b>Actions</b>
      </td>
    </tr>
    <?
      $ctr = 0;
      $COMIC_IDS = array();
      $res = db_query("SELECT * FROM comic_favs WHERE user_id='".$USER->user_id."'");
      while($row = db_fetch_object($res)) {
        $COMIC_IDS[$row->comic_id] = $row;
      }
      $num_favs = mysql_num_rows($res);
      db_free_result($res);

      if ( $USER->flags&FLAG_FAVS_BY_DATE ) {
        $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') ORDER BY last_update DESC");
      }
      else {
        $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') ORDER BY comic_name ASC");
      }

      while ($row = db_fetch_object($res))
      {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
        $bg  = '';
        if ( ++$ctr%2 == 0 ) {
          $bg = 'bgcolor="#3300CC"';
        }
        $indicator = '';
        if  ( date("Ymd",$row->last_update) == YMD ) {
          $indicator = ' *';
        }
        ?>
        <tr>
          <td height="20" valign="middle" class="community_thrd">
            <img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.$indicator?></a>
          </td>
          <td width="50" height="20" align="center" valign="middle" class="community_thrd">
            <input name="recommend_<?=$row->comic_id?>" type="checkbox" <?=( ($COMIC_IDS[$row->comic_id]->recommend==1)?"CHECKED":"")?>>
          </td>
          <td width="150" height="20" align="center" valign="middle" class="community_thrd">
            <input type="radio" id="email_<?=$row->comic_id?>_on" name="email_<?=$row->comic_id?>" value="1" <?=( ($COMIC_IDS[$row->comic_id]->email_on_update)?"CHECKED":"" )?>><label for="email_<?=$row->comic_id?>_on">On</label>
            <input type="radio" id="email_<?=$row->comic_id?>_off" name="email_<?=$row->comic_id?>" value="0" <?=( (!$COMIC_IDS[$row->comic_id]->email_on_update)?"CHECKED":"" )?>><label for="email_<?=$row->comic_id?>_off">Off</label>
          </td>
          <td width="50" height="20" align="center" valign="middle" class="community_thrd">
	   <a href="<?=$_SERVER['PHP_SELF']?>?d_comic_id=<?=$row->comic_id?>" ><img src="<?=IMAGE_HOST_SITE_GFX?>/account_overview/18815282.delete.gif" onclick="return confirm('Are you sure want to delete ?')" title="Delete" border="0"></a>
          </td>
        </tr>
        <?
      }
      if($num_favs ==0){
      ?>
        <tr>
          <td height="20" valign="middle"  colspan="4" align="center" class="community_thrd">
	      There are no Favorites
          </td>
	</tr>
      <?php
	}
      ?>
    <TR>
      <TD ALIGN='LEFT' colspan="2" valign="top" class="helpnote">
        <p>RECOMMENDED: If checked, this comic will appear in the list of your recommended comics on your comic's homepage.</p>
        <p>RECEIVE E-MAIL: Click the on button to receive an e-mail any time the comic is updated. </p>
      </TD>
      <TD ALIGN='LEFT' valign="top" class="helpnote">&nbsp;</TD>
      <TD ALIGN='right' valign="top" class="helpnote"><input type="image" src="<?=IMAGE_HOST_SITE_GFX?>/account_overview/faves_update.gif" width="100" height="24" border="0" align="baseline"></TD>
    </TR>
  </TABLE>
  </form>

</DIV>

<?
include('footer_overview.inc.php');
?>
