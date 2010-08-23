<?php
define('DEBUG_MODE', 0); // keep debug info from polluting response.
include_once('../../includes/global.inc.php');


$REQUEST = isset($_POST['request']) ? $_POST['request'] : $_GET['request'];




switch($REQUEST)
{
  case 'rack_new':
    $FILTER = 'new';
  break;

  case 'rack_random':
    $FILTER = 'random';
  break;

  case 'rack_10spot':
  default:
    $FILTER = '10spot';
  break;
}

$OBJ = get_ajax_settings( $USER );

?>
<form id="rack_settings">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
    <td colspan="3" align="left">
      <b>Type</b>
    </td>
  </tr>
  <tr>
    <td align="left">
      <input name="browsetype" type="checkbox" value="1" <?=( !isset($OBJ->rackfilter[$FILTER]['comic_type'][1]) ? 'CHECKED' : '' )?>>Comic Book/Story
    </td>
    <td align="left">
      <input name="browsetype" type="checkbox" value="0" <?=( !isset($OBJ->rackfilter[$FILTER]['comic_type'][0]) ? 'CHECKED' : '' )?>>Comic Strip
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

      ?><input type="checkbox" value="<?=$id?>" name="style[]" <?=( !isset($OBJ->rackfilter[$FILTER]['search_style'][$id]) ? 'CHECKED' : '' )?>><?=$name?><br><?
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

      ?><input type="checkbox" value="1" id="category_<?=$id?>"><?=$name?><br><?
    }
    ?>
  </tr>
</table>
</form>