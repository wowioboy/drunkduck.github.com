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
      if ( $_POST['preview'] )
      {
        ?>
        <table border="0" cellpadding="0" cellspacing="5" width="260">
          <tr>
            <td align="right" style="color:red;"><b>To:</b></td>
            <td align="left" style="color:red;"><?=$_POST['to_name']?></td>
          </tr>
          <tr>
            <td align="right" style="color:red;"><b>From:</b></td>
            <td align="left" style="color:red;"><?=$_POST['from_name']?></td>
          </tr>
        </table>
        <?
      }

      if ( !$USER )
      {
        ?><div align="center"><b>You must be logged in to send a holiday card</div><?
      }
      else if ( isset($_POST['preview']) )
      {
          if ( file_exists('../cards/card_'.((int)$_POST['card_id']).'.gif') ) {
            ?><img src="http://<?=DOMAIN?>/nontest/cards/card_<?=((int)$_POST['card_id'])?>.gif"><?
          }
          else if ( file_exists('../cards/card_'.((int)$_POST['card_id']).'.jpg') ) {
            ?><img src="http://<?=DOMAIN?>/nontest/cards/card_<?=((int)$_POST['card_id'])?>.jpg"><?
          }
        ?>
        <div style="width:300px;" align="center">
          <?=htmlentities($_POST['message'], ENT_QUOTES)?>
        </div>
        <br>
        <br>

        <div align="center">
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <input type="hidden" name="to_email" value="<?=$_POST['to_email']?>">
            <input type="hidden" name="to_name" value="<?=$_POST['to_name']?>">
            <input type="hidden" name="from_email" value="<?=$_POST['from_email']?>">
            <input type="hidden" name="from_name" value="<?=$_POST['from_name']?>">
            <input type="hidden" name="message" value="<?=$_POST['message']?>">
            <input type="hidden" name="card_id" value="<?=(int)$_POST['card_id']?>">
            <input type="hidden" name="confirm" value="yes">
            <input type="submit" value="Send It!" style="width:100px;height:30px;">
          </form>
          <br>
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <input type="hidden" name="to_email" value="<?=$_POST['to_email']?>">
            <input type="hidden" name="to_name" value="<?=$_POST['to_name']?>">
            <input type="hidden" name="from_email" value="<?=$_POST['from_email']?>">
            <input type="hidden" name="from_name" value="<?=$_POST['from_name']?>">
            <input type="hidden" name="message" value="<?=$_POST['message']?>">
            <input type="hidden" name="card_id" value="<?=(int)$_POST['card_id']?>">
            <input type="submit" value="Change It!" style="width:100px;height:30px;">
          </form>
        </div>
        <?
      }
      else if ( isset($_POST['confirm']) )
      {
        $HASH = md5($USER->user_id . strtolower(trim($_POST['to_email'])));
        db_query("INSERT INTO ecards_sent (msg_id, user_id, from_name, from_email, to_name, to_email, message, ymd_date, is_read, card_id) VALUES ('".$HASH."', '".$USER->user_id."', '".db_escape_string($_POST['from_name'])."', '".db_escape_string($_POST['from_email'])."', '".db_escape_string($_POST['to_name'])."', '".db_escape_string($_POST['to_email'])."', '".db_escape_string($_POST['message'])."', '".date("Ymd")."', '0', '".(int)$_POST['card_id']."')");
        if ( db_rows_affected() > 0 )
        {
          $MSG = "Hi ".$_POST['to_name'].",<br><br>";
          $MSG .= $_POST['from_name']." has sent you a card!<br><br>";
          $MSG .= '<a href="http://'.DOMAIN.'/nontest/sendcard/?msg='.$HASH.'">Click Here</a><br>';
          $MSG .= 'Or paste this URL into your browser:<br>http://'.DOMAIN.'/nontest/sendcard/?msg='.$HASH;
          sendMail($_POST['to_email'], $_POST['from_name']." has sent you a card!", $MSG, $_POST['from_email']);
        }
        ?><div align="center" style="color:white;"><b>Your card has been sent!</b><div><?
      }
      else if ($_GET['card_id'] || $_POST['card_id'])
      {
        ?>
        <div align="center">
        <?
        $card_id = (int)( ($_GET['card_id']) ? $_GET['card_id'] : $_POST['card_id'] );
          if ( file_exists('../cards/card_'.$card_id.'.gif') ) {
            ?><img src="http://<?=DOMAIN?>/nontest/cards/card_<?=$card_id?>.gif"><?
          }
          else if ( file_exists('../cards/card_'.$card_id.'.jpg') ) {
            ?><img src="http://<?=DOMAIN?>/nontest/cards/card_<?=$card_id?>.jpg"><?
          }
        ?>
        </div>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
          <table border="0" cellpadding="3" cellspacing="2" width="300">
            <tr>
              <td align="right" style="color:white;" width="100"><b>To Email:</b></td>
              <td align="left"><input type="text" name="to_email" style="width:100%;" value="<?=$_POST['to_email']?>"></td>
            </tr>
            <tr>
              <td align="right" style="color:white;" width="100"><b>To Name:</b></td>
              <td align="left"><input type="text" name="to_name" style="width:100%;" value="<?=$_POST['to_name']?>"></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td align="right" style="color:white;" width="100"><b>From Email:</b></td>
              <td align="left"><input type="text" name="from_email" style="width:100%;" value="<?=$_POST['from_email']?>"></td>
            </tr>
            <tr>
              <td align="right" style="color:white;" width="100"><b>From Name:</b></td>
              <td align="left"><input type="text" name="from_name" style="width:100%;" value="<?=$_POST['from_name']?>"></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td align="right" style="color:white;" width="100"><b>Message:</b></td>
              <td align="left"><input type="text" name="message" style="width:100%;" value="<?=$_POST['message']?>"></td>
            </tr>
          </table>
          <input type="Submit" value="Preview & Send!">
          <input type="hidden" name="preview" value="1">
          <input type="hidden" name="card_id" value="<?=$card_id?>">
        </form>
        <?
      }
      ?>