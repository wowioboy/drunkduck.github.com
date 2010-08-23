<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../../includes/global.inc.php');

$OBJ = get_ajax_settings( $USER );

$RACKNAME = 'new';

?>
<form id="rack_settings" onSubmit="saveFilter('<?=$RACKNAME?>', this);return false;" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
    <td colspan="3" align="left">
      <b>Type</b>
    </td>
  </tr>
  <tr>
    <td align="left">
      <input name="comic_type_filter[]" type="checkbox" value="1" <?=( !isset($OBJ->rackfilter[$RACKNAME]['comic_type'][1]) ? 'CHECKED' : '' )?>>Comic Book/Story
    </td>
    <td align="left">
      <input name="comic_type_filter[]" type="checkbox" value="0" <?=( !isset($OBJ->rackfilter[$RACKNAME]['comic_type'][0]) ? 'CHECKED' : '' )?>>Comic Strip
    </td>
  </tr>



  <tr>
    <td colspan="3" align="left">
      <b>Art Style</b>
    </td>
  </tr>
  <tr>
    <?
    $ct = -1;
    foreach( $COMIC_ART_STYLES as $id=>$name)
    {
      ++$ct;
      if ( $ct%3 == 0 ) {
        ?></td><td valign="top" width="33%"><?
      }
      else if ( $ct%16==0 ) {
        ?></tr><tr><?
      }

      ?><input type="checkbox" value="<?=$id?>" name="style_filter[]" <?=( !isset($OBJ->rackfilter[$RACKNAME]['search_style'][$id]) ? 'CHECKED' : '' )?>><?=$name?><br><?
    }
    ?>
  </tr>



  <tr>
    <td colspan="3" align="left">
      <b>Art Style</b>
    </td>
  </tr>
  <tr>
    <?
    $ct = -1;
    foreach( $COMIC_CATEGORIES as $id=>$name)
    {
      ++$ct;
      if ( $ct%6 == 0 ) {
        ?></td><td valign="top" width="33%"><?
      }
      else if ( $ct%16==0 ) {
        ?></tr><tr><?
      }

      ?><input type="checkbox" value="<?=$id?>" name="cat_filter[]" <?=( !isset($OBJ->rackfilter[$RACKNAME]['search_category'][$id]) ? 'CHECKED' : '' )?>><?=$name?><br><?
    }
    ?>
  </tr>



  <tr>
    <td colspan="3" align="left">
      <b>Rating</b>
    </td>
  </tr>
  <tr>
    <?
    $ct = 0;
    foreach( $RATINGS as $id=>$name)
    {
      ?><td valign="top" width="33%"><input type="checkbox" value="<?=$id?>" name="rating_filter[]" <?=( !isset($OBJ->rackfilter[$RACKNAME]['search_rating'][$id]) ? 'CHECKED' : '' )?>><?=array_shift( explode('.', $name) )?></td><?

      ++$ct;
      if ( $ct%3==0 ) {
        ?></tr><tr><?
      }
    }
    ?>
  </tr>




  <tr>
    <td colspan="3" align="center">
      <input type="submit" value="Save">
    </td>
  </tr>
</table>
<input type="hidden" name="editrack" value="1">
</form>