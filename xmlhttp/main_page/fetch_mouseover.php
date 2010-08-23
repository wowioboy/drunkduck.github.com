<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');

$COMIC_ID = (int)( isset($_POST['mouseover']) ? $_POST['mouseover'] : $_GET['mouseover'] );
$toolBoxType = $_REQUEST['toolBoxType'];

//If selected tooltip is not for news
if($toolBoxType!="news"){

$res = db_query("SELECT * FROM comics_tooltip_cache WHERE comic_id='".$COMIC_ID."'");
if ( $row = db_fetch_object($res) )
{
  if ( $row->cache_timestamp > time() - 300 ) {
    die($row->cache_data);
  }
  db_query("DELETE FROM comics_tooltip_cache WHERE comic_id='".$COMIC_ID."'");
}

$res = db_query("SELECT * FROM comics WHERE comic_id='".$COMIC_ID."'");
$row = db_fetch_object($res);
db_free_result($res);

$res = db_query("SELECT * FROM users WHERE user_id='".$row->user_id."'");
$uRow = db_fetch_object($res);
db_free_result($res);

ob_start();
?>
<table border="0" cellpadding="0" cellspacing="0" width="400">
  <tr>
    <td width="20" height="51"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_tl.png"></td>
    <td height="51" align="left" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_top.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_arrow.png"></td>
    <td width="23" height="51"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_tr.png"></td>
  </tr>

  <tr>
    <td width="20" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_l.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/spacer.png" width="20"></td>
    <td bgcolor="#ffffff" align="left">
      <div class="tipdiv_title"><?=$row->comic_name?></div>
      <div class="tipdiv_misc">
        <img src="<?=IMAGE_HOST_SITE_GFX?>/genre_icons/<?=$row->category?>.gif" alt="Genre: <?=$COMIC_CATS[$row->category]?>" width="12" height="12">
        <img src="<?=IMAGE_HOST_SITE_GFX?>/ratings/rating_<?=$row->rating_symbol?>_sm.gif" width="12" height="12">
        by
        <?=$uRow->username?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?=number_format($row->total_pages)?> pages
      </div>
      <div class="tipdiv_desc">
        <?=$row->description?>
      </div>
    </td>
    <td width="23" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_r.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/spacer.png" width="24"></td>
  </tr>

  <tr>
    <td width="20" height="24"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_bl.png"></td>
    <td height="24" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_bottom.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_bottom.png"></td>
    <td width="23" height="24"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_br.png"></td>
  </tr>
</table>
<?
$CONTENT = ob_get_clean();

db_query("INSERT INTO comics_tooltip_cache (comic_id, cache_data, cache_timestamp) VALUES ('".$COMIC_ID."', '".db_escape_string($CONTENT)."', '".time()."')");

}else{
//Selected tooltip is for news section

$res = db_query("SELECT blog_id, user_id, title, body, timestamp_date  as news_date FROM admin_blog WHERE blog_id='".$COMIC_ID."'");
$row = db_fetch_object($res);
db_free_result($res);

$res = db_query("SELECT * FROM users WHERE user_id='".$row->user_id."'");
$uRow = db_fetch_object($res);
db_free_result($res);

ob_start();


?>

<table border="0" cellpadding="0" cellspacing="0" width="270">
  <tr>
    <td width="20" height="51"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_tl.png"></td>
    <td height="51" align="left" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_top.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_arrow.png"></td>
    <td width="23" height="51"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_tr.png"></td>
  </tr>
  <tr>
    <td width="20" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_l.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/spacer.png" width="20"></td>
    <td bgcolor="#ffffff" align="left">
      <div class="tipdiv_title_12" ><b><?=BBCode($row->title)?></b></div>
      <div class="tipdiv_misc_11" >
        Posted by
        <?=$uRow->username?>
        <br>
      </div>
	<i><?=date("F j, Y - g:i a",$row->news_date)?></i>
      <div class="tipdiv_desc_11" >
        <?=substr(BBCode($row->body),0,300)?> ...
	<br>
	<a  href="http://<?=DOMAIN?>/news/news_archive.php?story=<?=$row->blog_id?>">[ Click the headline for more... ]</a>
      </div>
    </td>
    <td width="23" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_r.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/spacer.png" width="24"></td>
  </tr>
  <tr>
    <td width="20" height="24"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_bl.png"></td>
    <td height="24" background="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_bottom.png"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_bottom.png"></td>
    <td width="23" height="24"><img src="<?=IMAGE_HOST?>/site_gfx_new_v3/tooltip/bubble_br.png"></td>
  </tr>
</table>


<?php
$CONTENT = ob_get_clean();
}

die($CONTENT);
?>
