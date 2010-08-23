<div align="left" class="header_title">Manage Favorites</div>

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
  $emails_on  = array();
  $emails_off = array();

  foreach($_POST as $key=>$value)
  {
    if ( substr($key, 0, 6) == 'email_' )
    {
      $key = substr($key, 6);
      if ( $value == 1 ) {
        $emails_on[] = (int)$key;
      }
      else {
        $emails_off[] = (int)$key;
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

  /*Delete command*/
  if ( count($deletes) ){
    $delete_sql = "DELETE FROM comic_favs WHERE user_id='".$USER->user_id."' AND comic_id IN ('".implode("','", $deletes)."')";
    db_query($delete_sql);
  }

  if ( count($emails) )
  {
    db_query("UPDATE comic_favs SET email_on_update='0' WHERE user_id='".$USER->user_id."' AND comic_id NOT IN ('".implode("','", $emails)."')");
    db_query("UPDATE comic_favs SET email_on_update='1' WHERE user_id='".$USER->user_id."' AND comic_id IN ('".implode("','", $emails)."')");
  }

  if ( count($emails_off) )
  {
    db_query("UPDATE comic_favs SET email_on_update='1' WHERE user_id='".$USER->user_id."' AND comic_id NOT IN ('".implode("','", $emails_off)."')");
    db_query("UPDATE comic_favs SET email_on_update='0' WHERE user_id='".$USER->user_id."' AND comic_id IN ('".implode("','", $emails_off)."')");
  }
  ?>

  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <input type="submit" value="Update Preferences">
    <br>
    <p align="center">
      <select name="sort_by">
        <option value="<?=FLAG_FAVS_BY_DATE?>" <?=(($USER->flags&FLAG_FAVS_BY_DATE)?"SELECTED":"")?>>Sort by Update</option>
        <option value="0" <?=(!($USER->flags&FLAG_FAVS_BY_DATE)?"SELECTED":"")?>>Sort by Name</option>
      </select>
    </p>
  <table border="0" cellpadding="5" cellspacing="0" width="700" class="userlist" style="border:1px dashed white;">
      <tr>
        <td height="20" valign="middle" align="center"><b>Comic</b></td>
        <td height="20" valign="middle" align="center" width="50">
          <b>Recommend?</b>
        </td>
        <td height="20" valign="middle" align="center" width="150"><b>Receive Email?</b></td>
	 <td height="20" valign="middle" align="center" width="50">
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
          $bg = 'bgcolor="#b0b0b0"';
        }
        $indicator = '';
        if  ( date("Ymd",$row->last_update) == YMD ) {
          $indicator = ' *';
        }
        ?>
        <tr>
          <td height="20" valign="middle" <?=$bg?>><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.$indicator?></a></td>
          <td height="20" valign="middle" align="center" width="50" <?=$bg?>>
            <input type="checkbox" name="recommend_<?=$row->comic_id?>" <?=( ($COMIC_IDS[$row->comic_id]->recommend==1)?"CHECKED":"")?>><?=$row->recommend?>
          </td>
          <td height="20" valign="middle" align="center" width="150" <?=$bg?>>
            <input type="radio" id="email_<?=$row->comic_id?>_on" name="email_<?=$row->comic_id?>" value="1" <?=( ($COMIC_IDS[$row->comic_id]->email_on_update)?"CHECKED":"" )?>><label for="email_<?=$row->comic_id?>_on">On</label>
            <input type="radio" id="email_<?=$row->comic_id?>_off" name="email_<?=$row->comic_id?>" value="0" <?=( (!$COMIC_IDS[$row->comic_id]->email_on_update)?"CHECKED":"" )?>><label for="email_<?=$row->comic_id?>_off">Off</label>
          </td>
          <td height="20" valign="middle" align="center" width="50" <?=$bg?>>
            <a href="<?=$_SERVER['PHP_SELF']?>?d_comic_id=<?=$row->comic_id?>" ><img src="<?=IMAGE_HOST_SITE_GFX?>/account_overview/18815282.delete.gif" onclick="return confirm('Are you sure want to delete ?')" title="Delete" border="0"></a>

          </td>
        </tr>
        <?
      }
      db_free_result($res);
    ?>
  </table>
  <input type="submit" value="Update Preferences">
  </form>
