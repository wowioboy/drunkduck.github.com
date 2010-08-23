<?
  include('tikimail_header.inc.php');


  /*

  mailbox (username, title, message)

  CREATE TABLE mailbox
  (
    username  VARCHAR(20)  NOT NULL,
    title     VARCHAR(255) NOT NULL,
    message   TEXT,
    time_sent INT(11)      NOT NULL,
    INDEX(username, time_sent)
  );


  */

  include('tikimail_footer.inc.php');
?>