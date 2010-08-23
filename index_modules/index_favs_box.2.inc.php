<script>
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
</script>
<div class="controlsbox">
  <img src="<?=IMAGE_HOST?>/site_gfx_new/myfaves.png" width="280" height="25" />
  <div class="padding" style='border:1px solid black;' align='center'>
  
    <table width="260" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">
    <p align="right" style='width:260px;'>
      <?
      if ( $USER->avatar_ext )
      {
        if ( $USER->avatar_ext == 'swf' ) {   
          $INFO = getimagesize(WWW_ROOT.'/gfx/avatars/avatar_'.$USER->user_id.'.swf');
          echo get_flash_movie(IMAGE_HOST.'/avatars/avatar_'.$USER->user_id.'.swf', $INFO[0], $INFO[1]);
        }
        else { 
          echo "<IMG SRC='".IMAGE_HOST."/avatars/avatar_".$USER->user_id.".".$USER->avatar_ext."' height='50' width='50' align='left'>";
        }
      }
      ?>
      Hi <?=$USER->username?>!
      <br>
      <a href="http://<?=DOMAIN?>/account/index.php">Account Info</a>
      <br>
      <a href="<?=$_SERVER['PHP_SELF']?>?logout=1">Logout</a>
      <!--
      <br>
      <a href="#">Karma</a>
      -->
    </p>
        </td>
      </tr>
    </table>
    <table width="260" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">
        </td>
      </tr>
      <tr>
        <td colspan="2"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_sub_myfavorites.gif" width="260" height="20" /></td>
      </tr>
      <?
        $ctr = 0;
        $COMIC_IDS = array();
        $res = db_query("SELECT comic_id FROM comic_favs WHERE user_id='".$USER->user_id."'");
        while($row = db_fetch_object($res)) {
          $COMIC_IDS[] = $row->comic_id;
        }
        db_free_result($res);
        
        $res = db_query("SELECT comic_id, comic_name, last_update, category, flags FROM comics WHERE comic_id IN ('".implode("','", $COMIC_IDS)."')");
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
          <tr ID='fav_<?=$row->comic_id?>'>
            <td width="228" height="20" valign="middle" <?=$bg?>><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.$indicator?></a></td>
            <td width="30" height="20" valign="middle" <?=$bg?>><A HREF='JavaScript:killFav(<?=$row->comic_id?>);' ALT='Delete this favorite' TITLE='Delete this favorite'><img src="<?=IMAGE_HOST?>/site_gfx_new/remove_button.gif" width="12" height="12" border="0" /></a></td>
          </tr>
          <?
        }
        db_free_result($res);
      ?>
    </table>
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
    <table width="260" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_sub_mycomics.gif" width="260" height="20" /></td>
      </tr>
      <?
      if ( count($comicsArr) == 0 )
      {
        ?>
        <tr>
          <td width="200" height="20" valign="middle" style="color:white;">you currently don't have any comics</td>
          <td width="60" height="20" valign="middle">&nbsp;</td>
        </tr>
        <?
      }
      else 
      {
        foreach($comicsArr as $row)
        {
          $bg = '';
          if ( ++$ctr%2 == 0 ) {
            $bg = 'bgcolor="#3300CC"'; 
          }
          $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
          ?>
          <tr>
            <td width="210" height="20" valign="middle" <?=$bg?>><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.( (date("Ymd",$row->last_update)==YMD)?" *":"" )?></a></td>
            <td width="50" height="20" align="center" valign="middle" <?=$bg?>><a href="http://<?=DOMAIN?>/account/add_page.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/button_addpage.gif" width="13" height="16" border="0" /></a><a href="http://<?=DOMAIN?>/account/edit_template.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/button_edittemplate.gif" width="18" height="16" border="0" /></a><a href="http://<?=DOMAIN?>/account/edit_comic.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/button_info.gif" width="13" height="16" border="0" /></a></td>
          </tr>
          <?
        }
      }
      ?>
    </table>
    <table width="260" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><img src="<?=IMAGE_HOST?>/site_gfx_new/DD_sub_myassist.gif" width="260" height="20" /></td>
      </tr>
      <?
      if ( count($assistArr) == 0 )
      {
        ?>
        <tr>
          <td width="200" height="20" valign="middle" style="color:white;">you currently don't assist on any comics</td>
          <td width="60" height="20" valign="middle">&nbsp;</td>
        </tr>
        <?
      }
      else 
      {
        foreach($assistArr as $row)
        {
          $bg = '';
          if ( ++$ctr%2 == 0 ) {
            $bg = 'bgcolor="#3300CC"'; 
          }
          $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name).'/';
          ?>
          <tr>
            <td width="210" height="20" valign="middle" <?=$bg?>><img src="<?=IMAGE_HOST?>/site_gfx_new/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12" /> <a href="<?=$url?>"><?=$row->comic_name.( (date("Ymd",$row->last_update)==YMD)?" *":"" )?></a></td>
            <td width="50" height="20" align="center" valign="middle" <?=$bg?>><a href="http://<?=DOMAIN?>/account/add_page.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/button_addpage.gif" width="13" height="16" border="0" /></a><a href="http://<?=DOMAIN?>/account/edit_template.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/button_edittemplate.gif" width="18" height="16" border="0" /></a><a href="http://<?=DOMAIN?>/account/edit_comic.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST?>/site_gfx_new/button_info.gif" width="13" height="16" border="0" /></a></td>
          </tr>
          <?
        }
      }
      ?>
      <tr>
        <td height="20" colspan="2" valign="middle"><img src="<?=IMAGE_HOST?>/site_gfx_new/control_legend.gif" width="260" height="20" /></td>
      </tr>
    </table>
  </div>
</div>