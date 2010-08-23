<div align="left" style="margin-bottom:5px;">
  <div style="font-size:16px;font-weight:bold;" align="left">
    Trophies
  </div>
  <?
  include_once(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');

  $tList = explode(',', $viewRow->trophy_string);

  $ct = 0;
  foreach($tList as $id) {
    if ( isset($GLOBALS['TROPHIES'][$id]) )
    {
      ?><a href="http://<?=DOMAIN?>/trophies.php"><img src="<?=IMAGE_HOST?>/trophies/small/<?=$id?>.png" style="border:0px;" width="50" height="50" alt="<?=$GLOBALS['TROPHIES'][$id]['name']?>" title="<?=$GLOBALS['TROPHIES'][$id]['name']?>"></a><?
      if ( ++$ct%6 == 0 ) {
        ?> <?
      }
    }
  }
  ?>
</div>