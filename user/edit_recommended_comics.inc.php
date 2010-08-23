<style>
.content_body
{
  font: 11px Verdana, Arial, Helvetica, Geneva, sans-serif;
  color:#000000;
  background:#f8d689 url(http://images.drunkduck.com/site_gfx_new_v2/tan_page_bg.png) repeat-x 0 top;
}

.content_body a {
  color: #00305b;
  font-weight:bold;
}
.content_body a:hover {
  color: #0067cc;
}
.content_body a:visited {
  color: #00305b;
}

.content_body img {
  border: 1px solid black;
}
</style>

<style>
.recommended {
  background:#ffffff;
  border:1px solid black;
}
.notRecommended {
  background:none;
  border:0px;
}
</style>

<div class="content_body">

  <div style="background:url(<?=IMAGE_HOST?>/site_gfx_new_v2/page_title_bg.gif);height:40px;" align="left">
    <img src="<?=IMAGE_HOST?>/profile_gfx/profile_hdr.gif" style="margin-left:5px;margin-top:10px;float:left;border:0px;">
  </div>

  <div align="left" style="margin:5px;"><a href="http://<?=USER_DOMAIN?>/<?=$USER->username?>">.: Back to your profile</a></div>

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

  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
  <input type="submit" value="Save Changes">
    <br>
    <p align="center">
      <select name="sort_by">
        <option value="<?=FLAG_FAVS_BY_DATE?>" <?=(($USER->flags&FLAG_FAVS_BY_DATE)?"SELECTED":"")?>>Sort by Update</option>
        <option value="0" <?=(!($USER->flags&FLAG_FAVS_BY_DATE)?"SELECTED":"")?>>Sort by Name</option>
      </select>
    </p>
  <table border="0" cellpadding="5" cellspacing="0" width="700">
    <tr>
    <?
      $ctr = 0;
      $COMIC_IDS = array();
      $res = db_query("SELECT * FROM comic_favs WHERE user_id='".$USER->user_id."'");
      while($row = db_fetch_object($res)) {
        $COMIC_IDS[$row->comic_id] = $row;
      }
      db_free_result($res);

      if ( $USER->flags&FLAG_FAVS_BY_DATE ) {
        $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') ORDER BY last_update DESC");
      }
      else {
        $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", array_keys($COMIC_IDS))."') ORDER BY comic_name ASC");
      }

      $ct = -1;
      while ($row = db_fetch_object($res))
      {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
        if ( ++$ct%5 == 0 ) {
          ?></tr><tr><?
        }
        ?>
        <td id="fav_<?=$row->comic_id?>" align="center" valign="top" width="20%" <?=( ($COMIC_IDS[$row->comic_id]->recommend==1)?"class='recommended'":"class='notRecommended'")?>>
          <div align="center">
            <input type="checkbox" name="recommend_<?=$row->comic_id?>" <?=( ($COMIC_IDS[$row->comic_id]->recommend==1)?"CHECKED":"")?> onclick="if( $F(this)=='on' ) { $('fav_<?=$row->comic_id?>').className='recommended'; } else { $('fav_<?=$row->comic_id?>').className='notRecommended'; };">
          </div>
          <a href='<?=$url?>'><img src="<?=thumb_processor($row)?>" border="0"><br><?=$row->comic_name?></a>
        </td>
        <?
      }
      db_free_result($res);
    ?>
    </tr>
  </table>
  <input type="submit" value="Save Changes">
  </form>

  <p>&nbsp;</p>

</div>