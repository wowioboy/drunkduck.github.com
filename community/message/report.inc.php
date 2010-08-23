<?
  include('tikimail_header.inc.php');

  $res = db_query("SELECT * FROM mailbox WHERE mail_id='".(int)$_GET['report']."' AND username_to='".$USER->username."'");
  if ( $row = db_fetch_object($res))
  {
    if ( !$row->non_username_from ) {
      db_query("INSERT INTO reported_mail (mail_id, cpu_flagged) VALUES ('".$row->mail_id."', '0')");
    }
  }
?>
<DIV ALIGN='CENTER'>This mail has been reported.</DIV>

<?
include('tikimail_footer.inc.php');
?>