<?
  include('tikimail_header.inc.php');


  if ( $USER->flags & FLAG_NO_SENDING_PQ ) {
    echo "Sorry, your right to send PQ's has been revoked due to abuse.";
    return;
  }

  require(WWW_ROOT.'/includes/packages/wordfilter_package/load.inc.php');

if ( $_POST['to_username'] && $_POST['subject'] && $_POST['message'] )
{
  if (  $_POST['to_username'] == $USER->username )
  {
    echo "<DIV ALIGN='CENTER'>You can't send mail to yourself.</DIV>";
    return;
  }

  $res = db_query("SELECT username, flags FROM users WHERE username='".db_escape_string($_POST['to_username'])."'");
  if ( db_num_rows($res) < 1 )
  {
    echo "<DIV ALIGN='CENTER'>The user '".$_POST['to_username']."' does not exist.</DIV>";
    return;
  }

  $TO_ROW = db_fetch_object($res);
  db_free_result($res);

  // Check on COPPA restriction
  if ( !($TO_ROW->flags & FLAG_OVER_12) )
  {
    echo "<DIV ALIGN='CENTER'>This user cannot receive Tiki Mail.</DIV>";
    return;
  }

  if ( strlen($_POST['title']) > 255 )
  {
    echo "<DIV ALIGN='CENTER'>Your message subject was too long.</DIV>";
    return;
  }

  if ( strlen($_POST['title']) > 10240 )
  {
    echo "<DIV ALIGN='CENTER'>Your message body was too long.</DIV>";
    return;
  }

  // Check for bad words and flag it!
  $CPU_FLAG = 0;
  if ( doBadWordCheck($_POST['subject']) || doBadWordCheck($_POST['message']) ) {
    $CPU_FLAG = 1;
  }

  $TEST = strtolower($_POST['message']);

  db_query("INSERT INTO mailbox (username_to, username_from, title, message, time_sent) VALUES ('".$TO_ROW->username."', '".$USER->username."', '".db_escape_string($_POST['subject'])."', '".db_escape_string(html_entity_decode(htmlentities($_POST['message'], ENT_QUOTES), ENT_QUOTES))."', '".time()."')");
  if ( db_rows_affected() > 0 ) {
    if ( $CPU_FLAG ) {
      db_query("INSERT INTO reported_mail (mail_id, cpu_flagged) VALUES ('".db_get_insert_id()."', '".$CPU_FLAG."')");
    }

    db_query("UPDATE users SET pending_mail=pending_mail+1, flags=(flags|".FLAG_NEW_MAIL.") WHERE username='".$TO_ROW->username."'");
    echo "<DIV ALIGN='CENTER'>Your message has been sent!</DIV>";

    db_query("UPDATE pqs_sent SET counter=counter+1 WHERE ymd_date='".date("Ymd")."'");
    if ( db_rows_affected() < 1 ) {
      db_query("INSERT INTO pqs_sent (ymd_date, counter) VALUES ('".date("Ymd")."', '1')");
    }
  }
}

include('tikimail_footer.inc.php');
?>
