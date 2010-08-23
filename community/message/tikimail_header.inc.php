<h1 align="left">Personal Quack</h1>
<?
  if ( isset($_GET['open_mail']) )
  {
    if ( $_GET['open_mail'] == 1 ) {
      $USER->flags &= ~FLAG_FRIEND_ONLY_MAIL;
      db_query("UPDATE users SET flags='".$USER->flags."' WHERE username='".$USER->username."'");
    }
    else if ( $_GET['open_mail'] == 0 ) {
      $USER->flags |= FLAG_FRIEND_ONLY_MAIL;
      db_query("UPDATE users SET flags='".$USER->flags."' WHERE username='".$USER->username."'");
    }
  }

  $res        = db_query("SELECT COUNT(*) as total_mail FROM mailbox WHERE username_to='".$USER->username."'");
  $row        = db_fetch_object($res);
  $TOTAL_MAIL = $row->total_mail;
  db_free_result($res);
?>
<link rel="stylesheet" type="text/css" href="http://<?=DOMAIN?>/community/community_styles2.css">
<div class="pagecontent" style="width:90%;">

<div align="center">
  <a href='inbox.php'><img src="<?=IMAGE_HOST?>/mail/mail_button_inbox.gif" border='0' alt="<?=number_format($TOTAL_MAIL)?> messages <?=( ($USER->pending_mail)?"[".number_format($USER->pending_mail)." new]":"")?>" title="<?=number_format($TOTAL_MAIL)?> messages <?=( ($USER->pending_mail)?"[".number_format($USER->pending_mail)." new]":"")?>"></a>
  <img src="<?=IMAGE_HOST?>/mail/spacer.gif" width="50" height="10">
  <a href='author.php'><img src="<?=IMAGE_HOST?>/mail/mail_button_write.gif" border='0'></a>
</div>