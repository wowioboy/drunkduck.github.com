<h1 align="left">Trophies</h1>
<div style="background:#f9e9b6;color:#000000;margin-top:-10px;">
<?
include(WWW_ROOT.'/includes/trophies/trophy_data.inc.php');

?>
  <div style="width:400px;">Note: <i>Not all available trophies are shown.</i></div>

  <table border="0" cellpadding="5" cellspacing="0">
    <tr>
    <?
    $ct = -1;
    foreach( $GLOBALS['TROPHIES'] as $id=>$data )
    {
      if ( !$data['secret'] )
      {
        ++$ct;

        if ( $ct && ($ct%2 == 0) ) {
          ?></tr><tr><td colspan="4" style="border-bottom:1px solid black;">&nbsp;</td></tr><tr><?
        }

        ?>
        <td align="center" width="120">
          <img src="<?=IMAGE_HOST?>/trophies/large/<?=$id?>.png">
          <br>
          <b><?=$data['name']?></b>
        </td>
        <td align="left">
          <?=$data['criteria']?>
        </td>
        <?
      }
    }
    ?>
    </tr>
  </table>

</div>