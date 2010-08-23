<? include('community_header.inc.php'); ?>

<div class="community_div">

  <div align="left" class="community_general_container">
    &raquo; Emotes
  </div>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">
    <tr>
      <td align="left" class='community_hdr'>
        Emote Code
      </td>
      <td width="100" align="center" class='community_hdr'>
        Image
      </td>
    </tr>
    <?
    foreach($EMOTES as $code=>$image)
    {
      ?>
      <tr>
        <td align="left" class="community_thrd">
          <?=htmlentities($code, ENT_QUOTES)?>
        </td>
        <td width="100" align="center" class="community_thrd" <?=$bg?>>
          <img src="<?=IMAGE_HOST?>/community_gfx/emotes/<?=$image?>">
        </td>
      </tr>
      <?
    }
    ?>
  </table>

</div>

<? include('community_footer.inc.php'); ?>