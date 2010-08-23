<?php
define('NO_TRACK', 1);
define('DEBUG_MODE', 0); // keep debug info from polluting response.

include_once('../../includes/global.inc.php');

if ( !$USER ) {

  ?>alert('You are not logged in');<?
  return;

}

include(WWW_ROOT.'/includes/video_package/video_func.inc.php');
include(WWW_ROOT.'/includes/video_package/SearchableText.class.php');

$VID_TITLE    = $_POST['submitTitle'];
$VID_CONTENT  = $_POST['submitBody'];
$VID_DESC     = $_POST['submitDescription'];







$o = new SearchableText( stripslashes( $VID_CONTENT ) );

if ( $o->findTags() )
{
  foreach($o->embeddedList as $e)
  {
    if ( $id = addVideo( $USER->user_id, $e->url, $e->width, $e->height, $VID_TITLE, $VID_DESC, $e->type ) )
    {
      if ( grabVideo($USER->user_id, $id, $VID_TITLE, $VID_DESC) )
      {
        $res = db_query("SELECT * FROM pool_movies WHERE id='".$id."'");
        $poolRow = db_fetch_object($res);
        db_free_result($res);

        ob_start();
        ?>
        <div align="left" id="movie_<?=$id?>_preview" style="padding:5px;width:95%;">
          <font style="font-weight:bold;font-size:16px;"><a href="#" onClick="toggleBlind( 'movie_<?=$id?>' );return false;"><?=pickNonEmpty( $VID_TITLE, $poolRow->title, 'Video '.$id)?></a></font>
          <br>
          <?=nl2br( pickNonEmpty( $VID_DESC, $poolRow->description, 'No Description') )?>
        </div>

        <div align="center" style="display:none;" id="movie_<?=$id?>_view">
          <?
          $sz = scaleVideo( array($poolRow->width, $poolRow->height), array(400, 260) );
          ?>
          <embed src="<?=$poolRow->url?>" width="<?=$sz['width']?>" height="<?=$sz['height']?>" type="<?=$poolRow->movie_type?>" allowscriptaccess="never" allownetworking="internal" enablejsurl="false" enablehref="false" saveembedtags="true"> </embed>
        </div>
        <?
        $txt = ob_get_clean();

        $txt = str_replace("\n", '', $txt);
        $txt = str_replace("\r", '', $txt);
        $txt = str_replace("\t", '', $txt);

        $txt = str_replace("'", "\\'", $txt);

        ?>$('video_list').innerHTML = '<?=$txt?>' + $('video_list').innerHTML;<?
      }
      else
      {
        ?>alert('You already have this video.');<?
      }
    }
  }
}
else
{
  ?>alert('ERROR: No video found!');<?
  return;
}





return;

$ADMIN_NOTES = db_escape_string( trim($_POST['admin_notes']) );

if ( $_POST['del'] )
{
  db_query("DELETE FROM master_items WHERE item_id='".$ITEM_ID."'");
  db_query("DELETE FROM master_items_admin_notes WHERE item_id='".$ITEM_ID."'");
  ?>new Effect.BlindUp('editForm', {duration:.3} );<?
}
else if ( $ITEM_ID == 'new' )
{
  db_query("INSERT INTO master_items (name, item_region, item_type, item_category, item_alignment, tiki_tokens, rarity, equiploc_flags, description, filename, mod_strength, mod_speed, mod_int, mod_stamina, mod_luck, mod_magic, mod_hp_max, mod_def, mod_att) VALUES ('".$NAME."', '".$REGION."', '".$TYPE."', '".$CATEGORY."', '".$ALIGNMENT."', '".$TT."', '".$RARITY."', '".$EQUIP_FLAGS."', '".$DESCRIPTION."', '".$FILENAME."', '".$MOD_STR."', '".$MOD_SPEED."', '".$MOD_INT."', '".$MOD_STA."', '".$MOD_LUCK."', '".$MOD_MAGIC."', '".$MOD_HP."', '".$MOD_DEF."', '".$MOD_ATT."')");
  $id = db_get_insert_id();

  if ( strlen($ADMIN_NOTES) > 0 ) {
    db_query("INSERT INTO master_items_admin_notes (item_id, notes) VALUES ('".$ITEM_ID."', '".$ADMIN_NOTES."')");
  }

  ?>$('image_div').innerHTML = 'Item #<?=$id?><br><img id="item_image" src="<?=IMAGE_HOST?>/items/<?=$_POST['filename']?>" height="100" width="100">';<?
  ?>$('del_div').innerHTML   = '<label for="del_<?=$id?>">Delete?</label><input id="del_<?=$id?>" type="checkbox" name="del" value="1">';<?
  ?>$('item_id').value       = '<?=$id?>';<?
}
else
{
  $res = db_query("SELECT * FROM master_items WHERE item_id='".$ITEM_ID."'");
  if ( $row = db_fetch_object($res) )
  {
    db_query("UPDATE master_items SET name='".$NAME."', item_region='".$REGION."', item_type='".$TYPE."', item_category='".$CATEGORY."', item_alignment='".$ALIGNMENT."', tiki_tokens='".$TT."', rarity='".$RARITY."', equiploc_flags='".$EQUIP_FLAGS."', description='".$DESCRIPTION."', filename='".$FILENAME."', in_circulation='".$IC."', mod_strength='".$MOD_STR."', mod_speed='".$MOD_SPEED."', mod_int='".$MOD_INT."', mod_stamina='".$MOD_STA."', mod_luck='".$MOD_LUCK."', mod_magic='".$MOD_MAGIC."', mod_hp_max='".$MOD_HP."', mod_def='".$MOD_DEF."', mod_att='".$MOD_ATT."' WHERE item_id='".$ITEM_ID."'");

    if ( strlen($ADMIN_NOTES) > 0 ) {
      db_query("UPDATE master_items_admin_notes SET notes='".$ADMIN_NOTES."' WHERE item_id='".$ITEM_ID."'");
      if ( db_rows_affected() < 1 ) {
        db_query("INSERT INTO master_items_admin_notes (item_id, notes) VALUES ('".$ITEM_ID."', '".$ADMIN_NOTES."')");
      }
    }
    else {
      db_query("DELETE FROM master_items_admin_notes WHERE item_id='".$ITEM_ID."'");
    }

    if ( $row->filename != $_POST['filename'] ) {
      ?>$('item_image').src = '<?=IMAGE_HOST?>/items/<?=$_POST['filename']?>';<?
    }

    ?>alert('Saved!');<?
  }
  else {
    ?>alert('ERROR! Item not found.');<?
  }
}


?>