<?
  include('tikimail_header.inc.php');

  if ( $USER->flags & FLAG_NO_SENDING_PQ ) {
    echo "Sorry, your right to send PQ's has been revoked due to abuse.";
    return;
  }

  if ( $_GET['reply'] )
  {
    $res = db_query("SELECT * FROM mailbox WHERE mail_id='".(int)$_GET['reply']."' AND username_to='".$USER->username."'");
    if ( $REPLY_ROW = db_fetch_object($res) )
    {
      $REPLY_ROW->title = 'RE: '.$REPLY_ROW->title;
    }
    db_free_result($res);
  }
  else if ( $_GET['to'] )
  {
    $REPLY_ROW = new stdClass();
    $REPLY_ROW->username_from = $_GET['to'];
  }
?>
    <script language="JavaScript">
      function findUser(input)
      {
        ajaxCall('/xmlhttp/find_username.php?try='+input, userFound);
      }
      function userFound(resp)
      {
        $('foundusers').innerHTML = resp;
      }
      function insertFindUser(user)
      {
        $('to_username').value = user;
        $('foundusers').innerHTML = '';
      }
    </script>
<BR><BR>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="community_general_container">

  <FORM ACTION='send_mail.php' METHOD='POST'>
  <TR>
    <TD ALIGN='RIGHT' WIDTH='70' class='community_hdr'><B>To:</B></TD>
    <TD ALIGN='LEFT'><INPUT TYPE='TEXT' ID='to_username' NAME='to_username' STYLE='width:99%;' VALUE='<?=$REPLY_ROW->username_from?>' onkeyup="findUser(this.value);"><div id="foundusers" align="left"></div></TD>
  </TR>

  <TR>
    <TD ALIGN='RIGHT' WIDTH='70' class='community_hdr'><B>Subject:</B></TD>
    <TD ALIGN='LEFT'><INPUT TYPE='TEXT' NAME='subject' value="<?=htmlentities($REPLY_ROW->title, ENT_QUOTES)?>" STYLE='width:99%;'></TD>
  </TR>

  <TR>
    <TD ALIGN='RIGHT' WIDTH='70' class='community_hdr'><B>Message:</B></TD>
    <TD ALIGN='LEFT'>
      <script language="JavaScript">
        textareaName = 'message';
      </script>
      <script src="bbcode.js" language="JavaScript"></script>
      <TEXTAREA ID='message' NAME='message' STYLE='WIDTH:99%;' ROWS='10'><?=( ($REPLY_ROW->message)?"\n\n[quote=".$REPLY_ROW->username_from."]".htmlentities($REPLY_ROW->message, ENT_QUOTES)."[/quote]":"")?></TEXTAREA>
    </TD>
  </TR>
</TABLE>
<INPUT TYPE='SUBMIT' VALUE='Send' STYLE='width:200px;'>
</FORM>

<?
if ( $REPLY_ROW )
{
  ?>
  <script language="JavaScript">
  if ( 1 ) {
    $('message').focus();
  }
  </script>
  <?
}

include('tikimail_footer.inc.php');
?>