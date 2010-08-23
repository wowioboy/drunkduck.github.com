my_comics[1]
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="14" align="center" class="favorite_mini_text">
      g
    </td>
    <td width="14" align="center" class="favorite_mini_text">
      r
    </td>
    <td width="100" align="left" class="favorite_mini_text">
      &nbsp;&nbsp; name
    </td>
    <td width="60" align="center" class="favorite_mini_text">
      tools
    </td>
    <td width="30" align="center" class="favorite_mini_text">
      pages
    </td>
    <td width="82" align="center" class="favorite_mini_text">
      update
    </td>
  </tr>
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

    $ct = 0;
    foreach( $comicsArr as $row )
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
      <tr>
        <td width="12" align="center" class="favorite_mini_text">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12">
        </td>
        <td width="12" align="center" class="favorite_mini_text">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$row->rating_symbol?>_sm.gif" width="12" height="12">
        </td>
        <td width="100" align="left" class="favorite_mini_text">
          <div align="left" style="width:100px;height:15px;overflow:hidden;">
            &nbsp;&nbsp; <a href="http://<?=DOMAIN?>/<?=comicNameToFolder($row->comic_name)?>/"><?=$row->comic_name?></a>
          </div>
        </td>
        <td width="60" align="center" class="favorite_mini_text"><a href="http://<?=DOMAIN?>/account/comic/add_page.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_addpage.gif" border="0" height="16" width="13"></a><a href="http://<?=DOMAIN?>/account/comic/comic_design.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_edittemplate.gif" border="0" height="16" width="18"></a><a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_info.gif" border="0" height="16" width="13"></a></td>
        <td width="30" align="center" class="favorite_mini_text">
          <?=number_format($row->total_pages)?>
        </td>
        <td width="86" align="center" class="favorite_mini_text">
          <?
          if ( date("Ymd", $row->last_update) == date("Ymd") ) {
            ?><font style="color:#ff0000;">TODAY</font><?
          }
          else if ( date("Ymd", $row->last_update-86400) == date("Ymd", time()-86400) ) {
            ?><font style="color:#e69f2c;">YESTERDAY</font><?
          }
          else {
            echo date("m/d/Y", $row->last_update);
          }
          ?>
        </td>
      </tr>
      <?
    }

    $ct = 0;
    foreach( $assistArr as $row )
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
      <tr>
        <td width="12" align="center" class="favorite_mini_text">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12">
        </td>
        <td width="12" align="center" class="favorite_mini_text">
          <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$row->rating_symbol?>_sm.gif" width="12" height="12">
        </td>
        <td width="100" align="left" class="favorite_mini_text">
          <div align="left" style="width:100px;height:15px;overflow:hidden;">
            &nbsp;&nbsp; <a href="http://<?=DOMAIN?>/<?=comicNameToFolder($row->comic_name)?>/"><?=$row->comic_name?></a>
          </div>
        </td>
        <td width="60" align="center" class="favorite_mini_text"><a href="http://<?=DOMAIN?>/account/comic/add_page.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_addpage.gif" border="0" height="16" width="13"></a><a href="http://<?=DOMAIN?>/account/comic/comic_design.php?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_edittemplate.gif" border="0" height="16" width="18"></a><a href="http://<?=DOMAIN?>/account/comic/?cid=<?=$row->comic_id?>"><img src="<?=IMAGE_HOST_SITE_GFX?>/button_info.gif" border="0" height="16" width="13"></a></td>
        <td width="30" align="center" class="favorite_mini_text">
          <?=number_format($row->total_pages)?>
        </td>
        <td width="86" align="center" class="favorite_mini_text">
          <?
          if ( date("Ymd", $row->last_update) == date("Ymd") ) {
            ?><font style="color:#ff0000;">TODAY</font><?
          }
          else if ( date("Ymd", $row->last_update-86400) == date("Ymd", time()-86400) ) {
            ?><font style="color:#e69f2c;">YESTERDAY</font><?
          }
          else {
            echo date("m/d/Y", $row->last_update);
          }
          ?>
        </td>
      </tr>
      <?
    }

    ?>
</table>