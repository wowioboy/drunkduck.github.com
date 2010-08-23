<?
$res = db_query($GLOBALS['QUERY']);
while ($row = db_fetch_object($res)) {
  ?><div class="rack_thumb"><a href="http://<?=DOMAIN?>/<?=comicNameToFolder($row->comic_name)?>/"><img src="<?=thumb_processor($row)?>" border="0" style="border:1px solid #bbb;" onMouseOver="LinkTip.show('<?=$row->comic_id?>', this);this.className='rack_img_opaque';" onMouseOut="LinkTip.hide();this.className='rack_img_trans'" class="rack_img_trans"></a></div><?
}
db_free_result($res);
?>