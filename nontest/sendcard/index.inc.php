<?
include('../includes/global.inc.php');

if ( isset($_GET['msg']) )
{
  $res = db_query("SELECT * FROM ecards_sent WHERE msg_id='".db_escape_string($_GET['msg'])."'");
  if ( $row = db_fetch_object($res) ) {
    $MSG = $row->message;

    db_query("UPDATE ecards_sent SET is_read=is_read+1 WHERE msg_id='".db_escape_string($_GET['msg'])."'");
  }
}
    if ( isset($MSG) && isset($_GET['msg']) )
    {
      ?>
      <table border="0" cellpadding="0" cellspacing="5" width="260">
        <tr>
          <td align="right" style="color:red;"><b>To:</b></td>
          <td align="left" style="color:red;"><?=$row->to_name?></td>
        </tr>
        <tr>
          <td align="right" style="color:red;"><b>From:</b></td>
          <td align="left" style="color:red;"><?=$row->from_name?></td>
        </tr>
      </table>
      <?
    }
    else if ( $USER )
    {
      ?>
      <table border="0" cellpadding="0" cellspacing="5" width="260">
        <tr>
          <td align="right" style="color:red;"><b>To:</b></td>
          <td align="left" style="color:red;"><?=$USER->username?></td>
        </tr>
        <tr>
          <td align="right" style="color:red;"><b>From:</b></td>
          <td align="left" style="color:red;">DrunkDuck</td>
        </tr>
      </table>
      <?
    }
    if ( file_exists('../cards/card_'.$row->card_id.'.gif') ) {
      ?><img src="http://<?=DOMAIN?>/nontest/cards/card_<?=$row->card_id?>.gif"><?
    }
    else if ( file_exists('../cards/card_'.$row->card_id.'.jpg') ) {
      ?><img src="http://<?=DOMAIN?>/nontest/cards/card_<?=$row->card_id?>.jpg"><?
    }
  ?>
  <div style="width:300px;" align="center">
    <?=htmlentities($MSG, ENT_QUOTES)?>
  </div>
