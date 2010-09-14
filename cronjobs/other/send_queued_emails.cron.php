#!/usr/bin/php -q
<?php
  include('../cron_data.inc.php');
  include(WWW_ROOT.'/includes/mail_class/htmlMimeMail.php');

  define('SIGNATURE', "<br><br /><a href=\"http://www.drunkduck.com\"><img src=\"/gfx/DD_email_tag.gif\" border=\"0\"></a><br />The Webcomics Community.");

  $EMAILS = array();

  // I want these rows locked up ASAP before another cron comes along and possible doubles up. Hence a little redunant querying
  $res = db_query("SELECT * FROM email_queue WHERE locked='0' ORDER BY id DESC LIMIT 100");
  while( $row = db_fetch_object($res) ) {
    $EMAILS[$row->id] = $row;
  }
  db_free_result($res);


  if ( count($EMAILS) )
  {
    // Lets lock up those rows now
    db_query("UPDATE email_queue SET locked='1' WHERE id IN ('".implode("','", array_keys($EMAILS))."')");

    // Now to send out the emails.
    foreach($EMAILS as $E)
    {
      $mail = new htmlMimeMail();

      if ( strstr($E->body_txt, "\n") && !strstr($E->body_txt, "<br") ) {
        $E->body_txt = nl2br($E->body_txt);
      }

      $mail->setFrom($E->from_email.' <'.$E->from_email.'>');
      $mail->setSubject($E->subject_txt);
      $mail->setHtml($E->body_txt.SIGNATURE);
      $mail->send(array($E->to_email));
    }

    // Now lets delete it from the queue.
    db_query("DELETE FROM email_queue WHERE id IN ('".implode("','", array_keys($EMAILS))."')");
  }
?>