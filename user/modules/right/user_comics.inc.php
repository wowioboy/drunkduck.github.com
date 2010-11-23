<?
if ( IS_PUBLISHER == 1 )
{
?>
  <div style="height:50px;background:url(<?=$IMAGE_FOLDER?>/sub_title_hdr.gif);color:#ffffff;font-weight:bold;" align="left">
      <img src="<?=IMAGE_HOST?>/process/user_<?=$viewRow->user_id?>.<?=$viewRow->avatar_ext?>" style="border:0px;" align="left" height="50">
      <?
      $name = $viewRow->username;
      if ( $name{strlen($name)-1} == 's' ) {
        $name .= "'";
      }
      else {
        $name .= "'s";
      }
      echo '<div style="padding-top:20px;padding-left:10px;">'.$name . " COMIC RACK</div>";
      ?>
  </div>
<?
}
else
{
?>
  <div style="height:24px;background:url(<?=$IMAGE_FOLDER?>/sub_title_hdr.gif);color:#ffffff;font-weight:bold;" align="left">
    <div style="padding-left:5px;padding-top:4px;">Comics by <?=$viewRow->username?></div>
  </div>
<?
}
?>
<div style="background:#ffffff;margin-bottom:5px;" align="left">
  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <?
      $res = db_query("SELECT * FROM comics WHERE user_id='".$viewRow->user_id."'");
      $ct = -1;

      if ( db_num_rows($res) == 0 ) {
        ?><td align="left">None.</td><?
      }

      while($row = db_fetch_object($res))
      {
        $url = 'http://'.DOMAIN.'/'.comicNameToFolder($row->comic_name);
        if ( ++$ct%3 == 0 ) {
          ?></tr><tr><?
        }
        ?>
          <td align="center" valign="top" width="33%">
            <a href='<?=$url?>'><img src="<?=thumb_processor($row)?>" border="0">
            <br>
            <?=$row->comic_name?></a>
            <br>
<!-- PLATINUM STUDIOS FIX -->
             <div style="font-size:9px;color:#a5a5a5;"><?=number_format($row->total_pages)?> pgs 
<?php if ($row->user_id != '19085' && $row->secondary_author != '19085'): ?>
| Last: <?=((date("Ymd", $row->last_update)==YMD)?"Today":date("m/d/y", $row->last_update))?>
<?php endif; ?> 
</div>
          </td>
        <?
      }
      db_free_result($res);
      ?>
    </tr>
  </table>
</div>
