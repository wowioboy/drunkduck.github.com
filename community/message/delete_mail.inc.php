<?
  include('tikimail_header.inc.php');


  /*

  CREATE TABLE friends_list
  (
    username VARCHAR(20) NOT NULL,
    friend   VARCHAR(20) NOT NULL,
    PRIMARY KEY(username, friend)
  );

  CREATE TABLE mailbox
  (
    mail_id       INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username_to   VARCHAR(20)  NOT NULL,
    username_from VARCHAR(20)  NOT NULL,
    title         VARCHAR(255) NOT NULL,
    message       TEXT,
    time_sent     INT(11)      NOT NULL,
    INDEX(username_to, time_sent),
    INDEX(username_from)
  );

  CREATE TABLE reported_mail
  (
    mail_id     INT(11) NOT NULL PRIMARY KEY,
    cpu_flagged TINYINT NOT NULL DEFAULT '0'
  );

  */
  if ( $_POST['delete'] )
  {
    $IDS = array();
    foreach($_POST['delete'] as $id) {
      $IDS[(int)$id] = (int)$id;
    }

//    db_query("DELETE FROM reported_mail WHERE mail_id IN ('".implode("','", $IDS)."')");
    db_query("DELETE FROM mailbox WHERE mail_id IN ('".implode("','", $IDS)."') AND username_to='".$USER->username."'");
  }
  else if ( $_GET['delete'] )
  {
//    db_query("DELETE FROM reported_mail WHERE mail_id='".(int)$_GET['delete']."'");
    db_query("DELETE FROM mailbox WHERE mail_id='".(int)$_GET['delete']."' AND username_to='".$USER->username."'");
  }

  $res = db_query("SELECT COUNT(*) as unread FROM mailbox WHERE username_to='".$USER->username."' AND viewed='0'");
  $row = db_fetch_object($res);
  db_free_result($res);
  db_query("UPDATE users SET pending_mail='".$row->unread."' WHERE username='".$USER->username."'");

  header("Location: ./inbox.php");
  include('tikimail_footer.inc.php');
?>
